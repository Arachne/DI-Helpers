<?php

namespace Tests\Integration;

use Arachne\DI\ResolverInterface;
use ArrayObject;
use Codeception\TestCase\Test;
use Nette\DI\Container;

/**
 * @author Jáchym Toušek
 */
class ExtensionTest extends Test
{

	/** @var ResolverInterface */
	private $resolver;

	public function _before()
	{
		parent::_before();
		$this->resolver = $this->guy->grabService(Container::class)->getService('resolver');
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
