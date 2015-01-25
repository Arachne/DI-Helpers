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
use Nette\PhpGenerator\ClassType;
use Nette\Utils\AssertionException;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class DIHelpersExtension extends CompilerExtension
{

	const RESOLVER_NAME = 'arachne.di.resolver.name';

	/** @var string[] */
	private $resolvers = [];

	/**
	 * @param string $tag
	 * @param string $type
	 * @return string
	 */
	public function addResolver($tag, $type = NULL)
	{
		$this->resolvers[$tag] = $type;
		return $this->prefix('resolver.' . $tag);
	}

	/**
	 * @param string $tag
	 * @return string
	 */
	public function getResolver($tag)
	{
		if (!isset($this->resolvers[$tag])) {
			throw new AssertionException("Resolver for tag '$tag' is not registered.");
		}
		return $this->prefix('resolver.' . $tag);
	}

	public function beforeCompile()
	{
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
					$services[$name] = $key;
				}
			}

			$service = $this->prefix('resolver.' . $tag);

			$builder->addDefinition($service)
				->setClass('Arachne\DIHelpers\DIResolver')
				->setArguments([
					'services' => $services,
				])
				->setAutowired(FALSE);
		}

		$this->resolvers = [];
	}

	public function afterCompile(ClassType $class)
	{
		if ($this->resolvers) {
			throw new AssertionException("Some resolvers were added too late. Please do not use addResolver method in beforeCompile.");
		}
	}

}
