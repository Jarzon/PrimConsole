<?php
namespace Prim\Console\Service;

class Migration
{
    protected $version = '';
    protected $helper;

    public function __construct($helper = null)
    {
        if($helper === null) {
            $helper = new FileHelper();
        }

        $this->helper = $helper;
    }

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