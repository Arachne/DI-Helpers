<?php

namespace Tests\Unit;

use Arachne\DIHelpers\ResolverInterface;
use Arachne\DIHelpers\DIResolver;
use Codeception\TestCase\Test;
use Mockery;
use Mockery\MockInterface;
use Nette\DI\Container;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class DIResolverTest extends Test
{

	/** @var DIResolver */
	private $resolver;

	/** @var MockInterface */
	private $container;

	protected function _before()
	{
		$services = [
			'valid' => 'service1',
		];

		$this->container = Mockery::mock(Container::class);
		$this->resolver = new DIResolver($services, $this->container);
	}

	public function testImplement()
	{
		$this->assertInstanceOf(ResolverInterface::class, $this->resolver);
	}

	public function testResolverValid()
	{
		$this->setupContainerMock('service1');
		$this->assertEquals((object) [ 'service1' ], $this->resolver->resolve('valid'));
	}

	public function testResolverInvalid()
	{
		$this->assertSame(null, $this->resolver->resolve('invalid'));
	}

	public function testIterator()
	{
		$this->setupContainerMock('service1');

		$this->assertEquals([
			'valid' => (object) [ 'service1' ],
		], iterator_to_array($this->resolver->getIterator()));
	}

	private function setupContainerMock($name)
	{
		$this->container
			->shouldReceive('getService')
			->once()
			->with($name)
			->andReturn((object) [ $name ]);
	}

}
