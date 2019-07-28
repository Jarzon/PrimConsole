<?php
namespace Prim\Console;

use Composer\Script\Event;
use Prim\Console\Service\FileHelper;

class CreateProject
{
    public static function postCreateProject(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $projectname = ucfirst(basename(realpath(".")));

        FileHelper::replaceInFolder($vendorDir . '../', ['PrimBase', $projectname]);
    }
}