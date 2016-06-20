<?php

namespace Tests\Integration;

use ArrayObject;
use Codeception\Test\Unit;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class ExtensionTest extends Unit
{
    public function testResolver()
    {
        $resolver = $this->tester->getContainer()->getService('arachne.dihelpers.resolvers.tag.foo');

        $this->assertEquals(new ArrayObject(['foo1']), $resolver->resolve('name1'));
        $this->assertEquals(new ArrayObject(['foo2']), $resolver->resolve('name2'));
        $this->assertEquals(new ArrayObject(['foo2']), $resolver->resolve('name3'));
        $this->assertEquals(new ArrayObject(['foo3']), $resolver->resolve('name4'));
        $this->assertSame(null, $resolver->resolve('name5'));
        $this->assertSame(null, $resolver->resolve('name6'));
    }

    public function testIterator()
    {
        $iterator = $this->tester->getContainer()->getService('arachne.dihelpers.iterators.tag.foo');

        $this->assertEquals([
            new ArrayObject(['foo1']),
            new ArrayObject(['foo2']),
            new ArrayObject(['foo3']),
        ], iterator_to_array($iterator));
    }

    public function testIteratorResolver()
    {
        $resolver = $this->tester->getContainer()->getService('arachne.dihelpers.iteratorresolvers.tag.foo');

        $this->assertEquals([new ArrayObject(['foo1'])], iterator_to_array($resolver->resolve('name1')));
        $this->assertEquals([new ArrayObject(['foo2'])], iterator_to_array($resolver->resolve('name2')));
        $this->assertEquals([new ArrayObject(['foo2'])], iterator_to_array($resolver->resolve('name3')));
        $this->assertEquals([new ArrayObject(['foo3'])], iterator_to_array($resolver->resolve('name4')));
        $this->assertSame(null, $resolver->resolve('name5'));
        $this->assertSame(null, $resolver->resolve('name6'));
    }
}
