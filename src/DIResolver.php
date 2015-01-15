<?php

namespace Arachne\DIHelpers;

use Nette\DI\Container;
use Nette\Object;

/**
 * @author Jáchym Toušek
 */
class DIResolver extends Object implements ResolverInterface
{

	const NAME_KEY = 'arachne.resolver.name';

	/** @var string[] */
	private $services;

	/** @var Container */
	private $container;

	/**
	 * @param string[] $services
	 */
	public function __construct(array $services, Container $container)
	{
		$this->services = $services;
		$this->container = $container;
	}

	/**
	 * @param string $name
	 * @return object
	 */
	public function resolve($name)
	{
		return isset($this->services[$name]) ? $this->container->getService($this->services[$name]) : NULL;
	}

}
