<?php
namespace Prim\Console;

use Prim\Console\Service\Migration;

class UpdateCodeCommand extends Command
{
    public function __construct($input = null, $output = null)
    {
        parent::__construct($input, $output);

        $this
            ->setName('update:code')
            ->setDescription('Update code source');
    }

    public function exec()
    {
        $version = $this->getArgument('version');

        if (!$version) {
            $this->output->writeLine('✖ Missing project name or version');
            return;
        }

        $migration = new Migration();

        $migration->migration($version);

        return;
    }
}
