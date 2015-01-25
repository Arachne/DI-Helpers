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

	/**
	 * @param string $tag
	 * @param string $type
	 * @return string
	 */
	public function addResolver($tag, $type = NULL)
	{
		$builder = $this->getContainerBuilder();

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

		return $service;
	}

}
