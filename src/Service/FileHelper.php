<?php
namespace Prim\Console\Service;

class FileHelper
{
    static function copy(string $src, string $dst, array $whitelist = [])
    {
        $MVCFolders = self::getFilesList($src);

        foreach($MVCFolders as $folder) {
            if(in_array($folder, $whitelist) && $whitelist !== []) self::recursiveCopy($src.$folder, $dst.$folder);
        }
    }

    static function recursiveCopy(string $src, string $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ($file = readdir($dir))) {
            if($file != '.' && $file != '..') {
                if(is_dir("$src/$file")) {
                    self::recursiveCopy("$src/$file", "$dst/$file");
                }
                else {
                    copy("$src/$file", "$dst/$file");
                }
            }
        }
        closedir($dir);
    }

    static function copyFile(string $src, string $dst)
    {
        copy($src, $dst);
    }

    static function gitMove(string $source, string $destination) : array
    {
        $output = [];

        exec ( "git mv $source $destination" , $output);

        return $output;
    }

    /**
     * Look if file exist
     * */
    public static function fileExists(string $dest): bool
    {
        if (!file_exists($dest)) {
            throw new \Exception("✖ folder doesn't exist at $dest");
        }
        return true;
    }

    /**
     * Backup a not versioned file.
     *
     * TODO: Give the backed up files a unique name, using like the current time to avoid overriding them on multiple migrations
     * TODO: If we have to backup many files create another method that is gonna dispatch the array and call fileBackup
     * */
    public static function fileBackup($file)
    {
        if (!copy($file, $file . '.backup')) {
            throw new \Exception("✖ Failed to copy $file...");
        }

        return "✔ Make copy of $file";
    }

    /**
     * Insert text in file, create one if it doesn't exist
     */
    static function putInFile(string $filePath, string $text, bool $append = false)
    {
        $flags = 0;

        if($append) {
            $flags = FILE_APPEND;
        }

        if (!file_put_contents($filePath, $text, $flags) > 0) {
            throw new \Exception("✖ Error while writing file");
        }

        return "✔ Created file at $filePath";
    }

    static function replaceInFile(string $filePath, array $rows, $simpleString = false) : bool
    {
        $regexes = [];
        $replaces = [];

        foreach ($rows as $row) {
            $regex = self::parseRegex($row[0]);

            $replace = $row[1];

            // If it's an anonymous static function
            if($replace instanceof \Closure) {

                if (file_exists($filePath) === false || !is_writeable($filePath)) {
                    throw new \Exception("✖ File $filePath does not exist or isn't writable");
                }

                $fileContent = file_get_contents($filePath);
                $count = 0;
                if (!$fileContent = preg_replace_callback($regex, $replace, $fileContent, -1, $count)) {
                    throw new \Exception("✖ Error in preg_replace_callback()");
                }

                if (!file_put_contents($filePath, $fileContent) > 0) {
                    throw new \Exception("✖ Error while writing file");
                }

                return true;
            }

            $regexes[] = $regex;
            $replaces[] = $replace;
        }

        if (file_exists($filePath) === false || !is_writeable($filePath)) {
            throw new \Exception("✖ File $filePath does not exist or isn't writable");
        }

        $fileContent = file_get_contents($filePath);
        $count = 0;

        if($simpleString) {
            $fileContent = str_replace($regexes, $replaces, $fileContent, $count);
        }
        else {
            $fileContent = preg_replace($regexes, $replaces, $fileContent, -1, $count);
        }

        if ($fileContent === null) {
            throw new \Exception("✖ Error in preg_replace()");
        }
        if (!file_put_contents($filePath, $fileContent) > 0) {
            throw new \Exception("✖ Error while writing file");
        }

        return "✔ Migration on file $filePath done";
    }

    static function extractFromFile(string $filePath, string $regex) : array
    {
        if (file_exists($filePath) === false || !is_writeable($filePath)) {
            throw new \Exception("✖ File $filePath does not exist or isn't writable");
        }

        $fileContent = file_get_contents($filePath);

        $return = [];

        preg_match($regex, $fileContent, $return);

        return $return;
    }

    /**
     * Replace string using a regex in every files in a folder
     */
    static function replaceInFolder(string $folderPath, array $rows) : bool
    {
        $files = self::getFilesList($folderPath);

        $replaced = false;

        foreach ($files as $file) {
            $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;

            if(is_dir($folderPath . DIRECTORY_SEPARATOR . $file)) {
                if(self::replaceInFolder($filePath, $rows)) {
                    $replaced = true;
                }
            } else {
                if(self::replaceInFile($filePath, $rows)) {
                    $replaced = true;
                }
            }
        }

        return $replaced;
    }

    static function replaceRegex(array $matchs, array $subRegex)
    {
        $regex = self::parseRegex($subRegex[0]);
        $replace = $subRegex[1];

        return preg_replace($regex, $replace, $matchs[1]);
    }

    protected static function parseRegex($regex)
    {
        if(is_array($regex)) {
            $regex = self::sprintfArray(self::regexEsc(array_shift($regex)), $regex);
        } else $regex = self::regexEsc($regex);

        return $regex;
    }

    protected static function sprintfArray($format, $arr)
    {
        return call_user_func_array('sprintf', array_merge((array)$format, $arr));
    }

    /**
     * Get a list of the files in a folder
     */
    protected static function getFilesList(string $folderPath) : array
    {
        return array_diff(scandir($folderPath), array('..', '.'));
    }

    /**
     * Esc regex special chars
     */
    protected static function regexEsc(string $regex) :string
    {
        return '/'.preg_quote($regex, '/').'/';
    }

    static function mkdir($folderPath) : bool
    {
        if (!mkdir($folderPath, 0755, false)) {
            return false;
        }

        return true;
    }
}