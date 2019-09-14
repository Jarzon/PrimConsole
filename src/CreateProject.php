<?php
namespace Prim\Console;

use Composer\Script\Event;

class CreateProject
{
    public static function postCreateProject(Event $event)
    {
        $root = realpath(".");
        $projectname = ucfirst(basename($root));

        $replaces = [
            "PrimBase" => $projectname,
            "primbase" => strtolower($projectname)
        ];

        copy("$root/app/config/config.php.dist",  "$root/app/config/config.php");
        copy("$root/phinx.yml.dist", "$root/phinx.yml");

        foreach (self::recursiveGlob("$root/*.*") as $target) {
            if(is_dir($target)) {
                continue;
            }

            echo "applying variables to $target...\n";
            self::applyValues($target, $replaces);
        }

        foreach (self::recursiveGlob("$root/**/*.*") as $target) {
            if(is_dir($target) || strpos($target, 'vendor') !== false || strpos($target, 'node_modules') !== false) {
                continue;
            }

            echo "applying variables to $target...\n";
            self::applyValues($target, $replaces);
        }
    }

    protected static function applyValues($target, $replaces)
    {
        file_put_contents(
            $target,
            strtr(
                file_get_contents($target),
                $replaces
            )
        );
    }

    protected static function recursiveGlob($pattern)
    {
        $subPatterns = explode('/**/', $pattern);

        // Get sub dirs
        $dirs = glob(array_shift($subPatterns) . '/*', GLOB_ONLYDIR);

        // Get files in the current dir
        $files = glob($pattern);

        foreach ($dirs as $dir) {
            $subDirList = self::recursiveGlob($dir . '/**/' . implode('/**/', $subPatterns));

            $files = array_merge($files, $subDirList);
        }

        return $files;
    }
}
