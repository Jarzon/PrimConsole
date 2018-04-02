<?php

namespace PrimTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PrimTools\Provider\Console\Command;

/**
 * Example command for testing purposes.
 */
class UpdateCodeCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('update:code')
            ->setDescription('Update code source')
            ->addArgument('project', InputArgument::OPTIONAL, 'Target project')
            ->addArgument('version', InputArgument::OPTIONAL, 'Target version to upgrade to');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $input->getArgument('project');

        $version = $input->getArgument('version');

        if (!$project || !$version) {
            $output->writeln('✖ Missing project name or version');
            return;
        }

        $migration = $this->getService('migration');

        // TODO: Should be done in the constuctor but while still being able to stop the app if the project doesn't exist
        if($migration->init($project, $output)) {
            $migration->migration($version);
        }

        return;
    }
}
