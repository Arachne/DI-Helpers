<?php

/**
 * This file is part of the Arachne
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\DIHelpers;

use Nette\DI\CompilerExtension as BaseCompilerExtension;
use Nette\Utils\AssertionException;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class CompilerExtension extends BaseCompilerExtension
{

	/**
	 * @param string $class
	 * @return BaseCompilerExtension
	 */
	public function getExtension($class, $need = TRUE)
	{
		$extensions = $this->compiler->getExtensions($class);
		$count = count($extensions);
		if ($count > 1) {
			throw new AssertionException("Extension '$class' is installed $count times.");
		}
		if ($count < 1 && $need) {
			throw new AssertionException("Extension '$class' is not installed.");
		}
		return $count === 1 ? reset($extensions) : NULL;
	}

}
