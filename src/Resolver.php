<?php

/*
 * This file is part of the Arachne
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\DIHelpers;

use Nette\DI\Container;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class Resolver implements ResolverInterface
{
    /**
     * @var string[]
     */
    private $services;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param string[] $services
     */
    public function __construct(array $services, Container $container)
    {
        $this->services = $services;
        $this->container = $container;
    }

    /**
     * @param string $name
     *
     * @return object|null
     */
    public function resolve($name)
    {
        return isset($this->services[$name]) ? $this->container->getService($this->services[$name]) : null;
    }
}
