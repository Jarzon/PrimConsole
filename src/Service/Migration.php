<?php
namespace Prim\Console\Service;

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