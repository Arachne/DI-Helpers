<?php

/*
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
class IteratorResolversExtension extends CompilerExtension
{
    use TagHelpersTrait;

    const NAME_ATTRIBUTE = 'arachne.dihelpers.iteratorresolver';

    public function processTags()
    {
        $builder = $this->getContainerBuilder();

        foreach ($this->tags as $tag => $type) {
            $services = [];
            foreach ($builder->findByTag($tag) as $key => $attributes) {
                $names = (array) (isset($attributes[self::NAME_ATTRIBUTE]) ? $attributes[self::NAME_ATTRIBUTE] : $attributes);
                foreach ($names as $name) {
                    if (!is_string($name)) {
                        throw new AssertionException("Service '$key' has no resolver name for tag '$tag'.");
                    }
                    $services[$name][] = $key;
                }
            }

            $builder->addDefinition($this->prefixTag($tag))
                ->setClass('Arachne\DIHelpers\IteratorResolver')
                ->setArguments(
                    [
                        'services' => $services,
                    ]
                )
                ->setAutowired(false);
        }
    }
}
