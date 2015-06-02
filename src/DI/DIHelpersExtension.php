<?php

/**
 * This file is part of the Arachne
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\DIHelpers\DI;

use Nette\DI\CompilerExtension;
use Nette\Utils\AssertionException;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class DIHelpersExtension extends CompilerExtension
{

	const RESOLVER_NAME = 'arachne.di.resolver.name';

	/** @var string[] */
	private $resolvers = [];

	/** @var string[] */
	private $overrides = [];

	/** @var bool */
	private $freeze;

	/**
	 * @param string $tag
	 * @param string $type
	 * @return string
	 */
	public function addResolver($tag, $type = null)
	{
		if ($this->freeze) {
			throw new AssertionException("Usage of addResolver is only allowed in loadConfiguration.");
		}
		$this->resolvers[$tag] = $type;
	}

	/**
	 * @param string $tag
	 * @param string $service
	 */
	public function overrideResolver($tag, $service)
	{
		if ($this->freeze) {
			throw new AssertionException("Usage of overrideResolver is only allowed in loadConfiguration.");
		}
		$this->overrides[$tag] = $service;
	}

	/**
	 * @param string $tag
	 * @param bool $override
	 * @return string
	 */
	public function getResolver($tag, $override = true)
	{
		if (!$this->freeze) {
			throw new AssertionException("Usage of getResolver is only allowed in beforeCompile. Also make sure that DIHelpersExtension is registered before your extension.");
		}
		if ($override && isset($this->overrides[$tag])) {
			return $this->overrides[$tag];
		}
		if (!isset($this->resolvers[$tag])) {
			throw new AssertionException("Resolver for tag '$tag' is not registered.");
		}
		return $this->prefix('resolver.' . $tag);
	}

	public function beforeCompile()
	{
		$this->freeze = true;

		$builder = $this->getContainerBuilder();

		foreach ($this->resolvers as $tag => $type) {
			$services = [];
			foreach ($builder->findByTag($tag) as $key => $value) {
				$names = (array) (isset($value[self::RESOLVER_NAME]) ? $value[self::RESOLVER_NAME] : $value);
				if ($type) {
					$class = $builder->getDefinition($key)->getClass();
					if ($class !== $type && !is_subclass_of($class, $type)) {
						throw new AssertionException("Service '$key' is not an instance of '$type'.");
					}
				}
				foreach ($names as $name) {
					if (is_string($name)) {
						if (isset($services[$name])) {
							throw new AssertionException("Services '$services[$name]' and '$key' both have resolver name '$name' for tag '$tag'.");
						}
						$services[$name] = $key;
					} else {
						$services[] = $key;
					}
				}
			}

			$service = $this->prefix('resolver.' . $tag);

			$builder->addDefinition($service)
				->setClass('Arachne\DIHelpers\DIResolver')
				->setArguments([
					'services' => $services,
				])
				->setAutowired(false);
		}
	}

}
