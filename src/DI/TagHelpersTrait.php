<?php

/**
 * This file is part of the Arachne
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\DIHelpers\DI;

use Nette\Utils\AssertionException;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
trait TagHelpersTrait
{

    /** @var string[] */
    private $tags = [];

    /** @var string[] */
    private $overrides = [];

    /** @var bool */
    private $freeze;

    /**
     * @param string $tag
     * @param string $type
     * @return string
     */
    public function add($tag, $type = null)
    {
        if ($this->freeze) {
            throw new AssertionException('Usage of ' . __CLASS__ . '::add() is only allowed in loadConfiguration.');
        }
        $this->tags[$tag] = $type;
    }

    /**
     * @param string $tag
     * @param string $service
     */
    public function override($tag, $service)
    {
        if ($this->freeze) {
            throw new AssertionException('Usage of ' . __CLASS__ . '::override() is only allowed in loadConfiguration.');
        }
        $this->overrides[$tag] = $service;
    }

    /**
     * @param string $tag
     * @param bool $override
     * @return string
     */
    public function get($tag, $override = true)
    {
        if (!$this->freeze) {
            throw new AssertionException('Usage of ' . __CLASS__ . '::get() is only allowed in beforeCompile. Also make sure that ' . __CLASS__ . ' is registered before all extensions which use it.');
        }
        if ($override && isset($this->overrides[$tag])) {
            return $this->overrides[$tag];
        }
        if (!isset($this->tags[$tag])) {
            throw new AssertionException("Tag '$tag' is not registered.");
        }
        return $this->prefixTag($tag);
    }

    public function beforeCompile()
    {
        $this->freeze = true;

        $builder = $this->getContainerBuilder();

        foreach ($this->tags as $tag => $type) {
            if (!$type) {
                continue;
            }
            foreach ($builder->findByTag($tag) as $key => $value) {
                $class = $builder->getDefinition($key)->getClass();
                if ($class !== $type && !is_subclass_of($class, $type)) {
                    throw new AssertionException("Service '$key' is not an instance of '$type'.");
                }
            }
        }

        $this->processTags();
    }

    /**
     * @param string $tag
     * @return string
     */
    private function prefixTag($tag)
    {
        return $this->prefix('tag.' . $tag);
    }
}
