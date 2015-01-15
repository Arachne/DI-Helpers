<?php

namespace Arachne\DIHelpers\DI;

use Nette\DI\CompilerExtension;

/**
 * @author Jáchym Toušek
 */
class DIHelpersExtension extends CompilerExtension
{

	const TAG_RESOLVER = 'arachne.di.resolver';
	const RESOLVER_NAME = 'arachne.di.resolver.name';

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		foreach ($builder->findByTag(self::TAG_RESOLVER) as $resolver => $tag) {
			$services = [];
			foreach ($builder->findByTag($tag) as $key => $value) {
				$names = (array) (isset($value[self::RESOLVER_NAME]) ? $value[self::RESOLVER_NAME] : $value);
				foreach ($names as $name) {
					$services[$name] = $key;
				}
			}

			$builder->getDefinition($resolver)
				->setClass('Arachne\DIHelpers\DIResolver')
				->setArguments([
					'services' => $services,
				])
				->setAutowired(FALSE);
		}
	}

}
