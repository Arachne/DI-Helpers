<?php

namespace Tests\Integration;

use Arachne\Codeception\ConfigFilesInterface;
use Arachne\DI\ResolverInterface;
use ArrayObject;
use Codeception\TestCase\Test;
use Nette\DI\Container;

/**
 * @author Jáchym Toušek
 */
class ExtensionTest extends Test implements ConfigFilesInterface
{

	/** @var ResolverInterface */
	private $resolver;

	public function getConfigFiles()
	{
		return [
			'config/config.neon',
		];
	}

	public function _before()
	{
		$this->resolver = $this->guy->grabService(Container::class)->getService('arachne.dihelpers.resolver.foo');
	}

	public function testResolver()
	{
		$this->assertEquals(new ArrayObject([ 'foo1' ]), $this->resolver->resolve('name1'));
		$this->assertEquals(new ArrayObject([ 'foo2' ]), $this->resolver->resolve('name2'));
		$this->assertEquals(new ArrayObject([ 'foo2' ]), $this->resolver->resolve('name3'));
		$this->assertEquals(new ArrayObject([ 'foo3' ]), $this->resolver->resolve('name4'));
		$this->assertSame(NULL, $this->resolver->resolve('name5'));
		$this->assertSame(NULL, $this->resolver->resolve('name6'));
	}

}
