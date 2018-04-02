<?php

namespace Prim\Console\Provider;

use Pimple\Container;
use Prim\Console\Migration;

/**
 * Prim\Console Utilities Service Provider
 *
 * @author Jarzon <j@masterj.net>
 */
class MigrationServiceProvider implements \Pimple\ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['migration'] = function ($pimple) {
            $migration = new Migration($pimple);

            return $migration;
        };
    }
}
