<?php

namespace Prim\Console;

use Prim\Command;

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
            ->setDescription('Update code source');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $version = $input->getArgument('version');

        if (!$version) {
            $output->writeln('✖ Missing project name or version');
            return;
        }

        $migration = $this->getService('migration');

        // TODO: Should be done in the constuctor but while still being able to stop the app if the project doesn't exist
        if($migration->init($output)) {
            $migration->migration($version);
        }

        return;
    }
}
