<?php

namespace PrimTools\Provider;

use Pimple\Container;
use PrimTools\Pack;

/**
 * PrimTools Utilities Service Provider
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
