<?php

/**
 * This file is part of the Arachne
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\DIHelpers;

use Iterator;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class IteratorResolver implements ResolverInterface
{

    /** @var string[] */
    private $services;

    /** @var IteratorFactory */
    private $iteratorFactory;

    /**
     * @param string[][] $services
     */
    public function __construct(array $services, IteratorFactory $iteratorFactory)
    {
        $this->services = $services;
        $this->iteratorFactory = $iteratorFactory;
    }

    /**
     * @param string $name
     * @return Iterator
     */
    public function resolve($name)
    {
        return isset($this->services[$name]) ? $this->iteratorFactory->create($this->services[$name]) : null;
    }
}
