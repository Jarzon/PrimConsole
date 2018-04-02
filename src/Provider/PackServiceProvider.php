<?php

namespace Prim\Console\Provider;

use Pimple\Container;
use Prim\Console\Pack;

/**
 * Prim\Console Utilities Service Provider
 *
 * @author Jarzon <j@masterj.net>
 */
class PackServiceProvider implements \Pimple\ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['pack'] = function ($pimple) {
            $pack = new Pack($pimple);

            return $pack;
        };
    }
}
