<?php

namespace Arachne\DIHelpers;

/**
 * @author Jáchym Toušek
 */
interface ResolverInterface
{

	/**
	 * @param string $name
	 * @return object
	 */
	public function resolve($name);

}
