<?php

namespace Tests\Unit;

use Arachne\DIHelpers\IteratorFactory;
use Arachne\DIHelpers\IteratorResolver;
use Arachne\DIHelpers\ResolverInterface;
use ArrayIterator;
use Codeception\MockeryModule\Test;
use Mockery;
use Mockery\MockInterface;
use Nette\DI\Container;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class IteratorResolverTest extends Test
{

    /** @var Resolver */
    private $resolver;

    /** @var MockInterface */
    private $factory;

    protected function _before()
    {
        $services = [
            'valid' => [
                'service1',
            ],
        ];

        $this->factory = Mockery::mock(IteratorFactory::class);
        $this->resolver = new IteratorResolver($services, $this->factory);
    }

    public function testImplement()
    {
        $this->assertInstanceOf(ResolverInterface::class, $this->resolver);
    }

    public function testValid()
    {
        $this->factory
            ->shouldReceive('create')
            ->once()
            ->with([ 'service1' ])
            ->andReturn(new ArrayIterator([ (object) [ 'service1' ] ]));

        $this->assertEquals([ (object) [ 'service1' ] ], iterator_to_array($this->resolver->resolve('valid')));
    }

    public function testInvalid()
    {
        $this->assertSame(null, $this->resolver->resolve('invalid'));
    }
}
