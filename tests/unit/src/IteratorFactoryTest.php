<?php

namespace Tests\Unit;

use Arachne\DIHelpers\IteratorFactory;
use Codeception\MockeryModule\Test;
use Mockery;
use Mockery\MockInterface;
use Nette\DI\Container;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class IteratorFactoryTest extends Test
{

	/** @var IteratorFactory */
	private $factory;

	/** @var MockInterface */
	private $container;

	protected function _before()
	{
		$this->container = Mockery::mock(Container::class);
		$this->factory = new IteratorFactory($this->container);
	}

	public function testCreate()
	{
		$this->container
			->shouldReceive('getService')
			->once()
			->with('service1')
			->andReturn((object) [ 'service1' ]);

		$this->container
			->shouldReceive('getService')
			->once()
			->with('service2')
			->andReturn((object) [ 'service2' ]);

		$services = [
			'service1',
			'service2',
		];

		$this->assertEquals([
			(object) [ 'service1' ],
			(object) [ 'service2' ]
		], iterator_to_array($this->factory->create($services)));
	}

}
