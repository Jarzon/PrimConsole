<?php

namespace Prim\Console\Provider;

use Pimple\Container;
use Prim\Console\Provider\Console\ContainerAwareApplication;

/**
 * Cilex Console Service Provider
 *
 * @author Beau Simensen <beau@dflydev.com>
 */
class ConsoleServiceProvider implements \Pimple\ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['console'] = function($pimple) {
            $console = new ContainerAwareApplication($pimple['console.name'], $pimple['console.version']);
            $console->setDispatcher($pimple['dispatcher']);
            $console->setContainer($pimple);

            return $console;
        };
    }
}
