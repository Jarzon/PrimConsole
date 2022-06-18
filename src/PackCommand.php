<?php

namespace Prim\Console;

use Prim\Console\Service\FileHelper;

class PackCommand extends Command
{
    protected string $projectPath = '/';
    protected string $projectName = '';
    public string $name = '';

    public function __construct(array $options, $input = null, $output = null)
    {
        parent::__construct($options, $input, $output);

        $this
            ->setName('pack:create')
            ->setDescription('Create a new pack');
    }

    public function exec()
    {
        $packName = $this->input->getArgument(0);

        if (!$packName) {
            $this->output->writeLine('✖ Missing pack name');
            return;
        }

        $basePack = 'BasePack';

        if ($this->input->getFlag('crud')) {
            $basePack = 'CrudPack';
        }

        $this->create($packName, $basePack);

        return;
    }

    public function create(string $name, string $basePackName): bool
    {
        $projectName = $this->options['project_name'];
        $name = $this->options['root'];

        $packName = '';
        $itemName = '';

        if($pos = strpos($name, 'Pack') !== false) {
            $packName = $name;
            $itemName = substr($name,0,$pos);
        } else {
            $itemName = $name;
            $packName = "{$name}Pack";
        }

        $packPath = "{$this->projectPath}/src/$packName/";

        // Look if the project and the pack exist
        if (FileHelper::fileExists($packPath)) {
            $this->output->writeLine("✖ Pack folder already exist") ;
            return false;
        }

        $this->name = $packName;

        FileHelper::recursiveCopy(realpath(__DIR__) . '/Packs/', $packPath);

        FileHelper::replaceInFolder("/src/$packName/", [
            ['BasePack', $packName],
            ['PrimBase', ucfirst($this->projectName)],
        ]);

        return true;
    }
}
