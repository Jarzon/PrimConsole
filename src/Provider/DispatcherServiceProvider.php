<?php

namespace Prim\Console\Provider;

use Pimple\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Registers EventDispatcher and related services with the Pimple Container
 *
 * @api
 */
class DispatcherServiceProvider implements \Pimple\ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Container $pimple)
    {
        $pimple['dispatcher'] = function () {
            return new EventDispatcher;
        };
    }
}
