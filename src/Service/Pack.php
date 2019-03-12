<?php
namespace Prim\Console\Service;

class Pack
{
    protected $projectPath = '/';
    protected $projectName = '';
    protected $name = '';

    protected $helper;

    public function __construct(string $projectPath, string $projectName, $helper = null)
    {
        $this->projectPath = $projectPath;
        $this->projectName = $projectName;

        if($helper === null) {
            $helper = new Helpers();
        }

        $this->helper = $helper;
    }

    public function create(string $name, string $basePackName): bool
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