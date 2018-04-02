<?php
namespace PrimTools;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

use PrimTools\Utilities;

class Migration extends Utilities
{
    private $version = '';

    /**
     * Do a migration
     * */
    public function migration(string $version) : bool
    {
        $version = realpath(__DIR__)."/Migrations/$version.php";

        if (!$this->fileExists($version)) {
            return false;
        }

        $this->version = $version;

        include($this->version);

        return true;
    }
}