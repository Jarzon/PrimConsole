<?php
namespace Prim\Console;

use Composer\Script\Event;
use Prim\Console\Service\FileHelper;

class CreateProject
{
    public static function postCreateProject(Event $event)
    {
        $root = $event->getComposer()->getConfig()->get('vendor-dir') . '../';
        $projectname = ucfirst(basename(realpath(".")));

        FileHelper::copyFile($root . 'app/config/config.php.dist', $root . 'app/config/config.php');
        FileHelper::copyFile($root . 'phinx.yml.dist', $root . 'phinx.yml');

        FileHelper::replaceInFolder($root, ['PrimBase', $projectname]);
    }
}