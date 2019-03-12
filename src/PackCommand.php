<?php

namespace Prim\Console;

use Prim\Console\Service\Pack;

/**
 * Example command for testing purposes.
 */
class PackCommand extends Command
{
    public function __construct(Console $console, $input = null, $output = null)
    {
        parent::__construct($console, $input, $output);

        $this
            ->setName('pack:create')
            ->setDescription('Create a new pack');
    }

    public function exec()
    {
        $configs = $this->console->getConfigs();

        $projectName = $configs['project_name'];

        $packName = $this->input->getArgument(0);

        if (!$packName) {
            $this->output->writeLine('✖ Missing pack name');
            return;
        }

        $basePack = 'BasePack';

        if ($this->input->getFlag('crud')) {
            $basePack = 'CrudPack';
        }

        $pack = new Pack($configs['root'], $projectName);

        $pack->create($packName, $basePack);

        return;
    }
}
