<?php

namespace PrimTools\Provider;

use Pimple\Container;
use PrimTools\Migration;

/**
 * PrimTools Utilities Service Provider
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
