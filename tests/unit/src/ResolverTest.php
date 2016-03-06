<?php

namespace Tests\Unit;

use Arachne\DIHelpers\Resolver;
use Arachne\DIHelpers\ResolverInterface;
use Codeception\MockeryModule\Test;
use Mockery;
use Mockery\MockInterface;
use Nette\DI\Container;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class ResolverTest extends Test
{

    /** @var Resolver */
    private $resolver;

    /** @var MockInterface */
    private $container;

    protected function _before()
    {
        $services = [
            'valid' => 'service1',
        ];

        $this->container = Mockery::mock(Container::class);
        $this->resolver = new Resolver($services, $this->container);
    }

    public function testImplement()
    {
        $this->assertInstanceOf(ResolverInterface::class, $this->resolver);
    }

    public function testValid()
    {
        $this->container
            ->shouldReceive('getService')
            ->once()
            ->with('service1')
            ->andReturn((object) [ 'service1' ]);

        $this->assertEquals((object) [ 'service1' ], $this->resolver->resolve('valid'));
    }

    public function testInvalid()
    {
        $this->assertSame(null, $this->resolver->resolve('invalid'));
    }
}
