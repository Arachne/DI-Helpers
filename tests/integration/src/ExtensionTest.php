<?php

namespace Tests\Integration;

use Arachne\Bootstrap\Configurator;
use ArrayObject;
use Codeception\Test\Unit;
use Nette\DI\Container;

/**
 * @author Jáchym Toušek
 */
class ExtensionTest extends Unit
{
    public function testResolver()
    {
        $container = $this->createContainer('config.neon');
        $resolver = $container->getService('arachne.dihelpers.resolvers.tag.foo');

        $this->assertEquals(new ArrayObject([ 'foo1' ]), $resolver->resolve('name1'));
        $this->assertEquals(new ArrayObject([ 'foo2' ]), $resolver->resolve('name2'));
        $this->assertEquals(new ArrayObject([ 'foo2' ]), $resolver->resolve('name3'));
        $this->assertEquals(new ArrayObject([ 'foo3' ]), $resolver->resolve('name4'));
        $this->assertSame(null, $resolver->resolve('name5'));
        $this->assertSame(null, $resolver->resolve('name6'));
    }

    public function testIterator()
    {
        $container = $this->createContainer('config.neon');
        $iterator = $container->getService('arachne.dihelpers.iterators.tag.foo');

        $this->assertEquals([
            new ArrayObject([ 'foo1' ]),
            new ArrayObject([ 'foo2' ]),
            new ArrayObject([ 'foo3' ]),
        ], iterator_to_array($iterator));
    }

    public function testIteratorResolver()
    {
        $container = $this->createContainer('config.neon');
        $resolver = $container->getService('arachne.dihelpers.iteratorresolvers.tag.foo');

        $this->assertEquals([ new ArrayObject([ 'foo1' ]) ], iterator_to_array($resolver->resolve('name1')));
        $this->assertEquals([ new ArrayObject([ 'foo2' ]) ], iterator_to_array($resolver->resolve('name2')));
        $this->assertEquals([ new ArrayObject([ 'foo2' ]) ], iterator_to_array($resolver->resolve('name3')));
        $this->assertEquals([ new ArrayObject([ 'foo3' ]) ], iterator_to_array($resolver->resolve('name4')));
        $this->assertSame(null, $resolver->resolve('name5'));
        $this->assertSame(null, $resolver->resolve('name6'));
    }

    private function createContainer($file)
    {
        $config = new Configurator();
        $config->setTempDirectory(TEMP_DIR);
        $config->addConfig(__DIR__ . '/../config/' . $file);
        return $config->createContainer();
    }
}
