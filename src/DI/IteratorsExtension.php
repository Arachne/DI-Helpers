<?php

/**
 * This file is part of the Arachne
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\DIHelpers\DI;

use Nette\DI\CompilerExtension;
use Nette\Utils\AssertionException;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class IteratorsExtension extends CompilerExtension
{
    use TagHelpersTrait;

    public function processTags()
    {
        $this->freeze = true;

        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('iteratorFactory'))
            ->setClass('Arachne\DIHelpers\IteratorFactory');

        foreach ($this->tags as $tag => $type) {
            $builder->addDefinition($this->prefixTag($tag))
                ->setClass('Iterator')
                ->setFactory('@Arachne\DIHelpers\IteratorFactory::create', [
                    array_keys($builder->findByTag($tag)),
                ])
                ->setAutowired(false);
        }
    }
}
