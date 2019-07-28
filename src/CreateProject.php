<?php
namespace Prim\Console;

use Composer\Script\Event;
use Prim\Console\Service\FileHelper;

class CreateProject
{
    public static function postCreateProject(Event $event)
    {
        $root = realpath(".");
        $projectname = ucfirst(basename($root));

        FileHelper::copyFile($root . 'app/config/config.php.dist', $root . 'app/config/config.php');
        FileHelper::copyFile($root . 'phinx.yml.dist', $root . 'phinx.yml');

        FileHelper::replaceInFolder($root, ['PrimBase', $projectname]);
    }
}