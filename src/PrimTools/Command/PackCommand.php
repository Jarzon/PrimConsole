<?php

namespace PrimTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PrimTools\Provider\Console\Command;

/**
 * Example command for testing purposes.
 */
class PackCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('pack:create')
            ->setDescription('Create a new pack')
            ->addArgument('project', InputArgument::OPTIONAL, 'Target project')
            ->addArgument('name', InputArgument::OPTIONAL, 'Pack name')
            ->addOption('crud', 'c', InputOption::VALUE_NONE, 'If set, the pack is gonna contain a basic CRUD');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $input->getArgument('project');

        $name = $input->getArgument('name');

        if (!$project || !$name) {
            $output->writeln('✖ Missing project name or pack name');
            return;
        }

        $packName = 'BasePack';

        if ($input->getOption('crud')) {
            $packName = 'CrudPack';
        }

        $pack = $this->getService('pack');

        if($pack->init($project, $output)) {
            $pack->create($name, $packName);
        }

        return;
    }
}
