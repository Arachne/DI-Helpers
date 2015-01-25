<?php

namespace Tests\Integration\Classes;

use Arachne\DIHelpers\DI\DIHelpersExtension;
use Arachne\DIHelpers\CompilerExtension;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class TestExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$this->getExtension(DIHelpersExtension::class)->addResolver('foo', 'ArrayObject');
	}

}
