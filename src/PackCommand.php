<?php

namespace Prim\Console;

use Prim\Console\Service\Pack;

/**
 * Example command for testing purposes.
 */
class PackCommand extends Command
{
    public function __construct($input = null, $output = null)
    {
        parent::__construct($input, $output);

        $this
            ->setName('pack:create')
            ->setDescription('Create a new pack');
    }

    public function exec()
    {
        $name = $this->input->getParameter('name');

        if (!$name) {
            $this->output->writeLine('✖ Missing project name or pack name');
            return;
        }

        $packName = 'BasePack';

        if ($this->input->getFlag('crud')) {
            $packName = 'CrudPack';
        }

        $pack = new Pack();

        $pack->create($name, $packName);

        return;
    }
}
