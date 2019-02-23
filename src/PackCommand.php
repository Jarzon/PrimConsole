<?php

namespace Prim\Console\Command;

use Prim\Command;

/**
 * Example command for testing purposes.
 */
class PackCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('pack:create')
            ->setDescription('Create a new pack')
            ->addArgument('name', InputArgument::OPTIONAL, 'Pack name')
            ->addOption('crud', 'c', InputOption::VALUE_NONE, 'If set, the pack is gonna contain a basic CRUD');
    }

    protected function execute()
    {
        $name = $input->getArgument('name');

        if (!$name) {
            $this->output ->writeLinen('✖ Missing project name or pack name');
            return;
        }

        $packName = 'BasePack';

        if ($input->getOption('crud')) {
            $packName = 'CrudPack';
        }

        $pack = $this->getService('pack');

        if($pack->init($output)) {
            $pack->create($name, $packName);
        }

        return;
    }
}
