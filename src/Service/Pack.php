<?php
namespace Prim\Console\Service;

class Pack extends Utilities
{
    private $name = '';

    /**
     * Do a migration
     * */
    public function create(string $name, string $basePackName) : bool
    {
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
        if ($this->fileExists($packPath)) {
            $this->output->writeLine("✖ Pack folder already exist") ;
            return false;
        }

        $this->name = $packName;

        $this->recursiveCopy(realpath(__DIR__) . '/Packs/', $packPath);

        $this->replaceInFolder("/src/$packName/", [
            ['BasePack', $packName],
            ['PrimBase', ucfirst($this->project)],
        ]);

        return true;
    }
}