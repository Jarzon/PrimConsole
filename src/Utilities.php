<?php
namespace Prim\Console;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

class Utilities
{
    public $project = '';
    public $projectPath = '';
    public $output;
    public $pimple;

    public function __construct($pimple, ConsoleOutputInterface $output = null) {
        $this->pimple = $pimple;

        if ($output === null) {
            $output = new ConsoleOutput();
        }

        $this->output = $output;
    }

    /**
     * Inject
     * */
    public function init(string $project) : bool
    {
        $this->project = $project;
        $this->projectPath = realpath(__DIR__ . '../../../../../../');

        return true;
    }

    function copy(string $src, string $dst, array $whitelist = [])
    {
        $MVCFolders = $this->getFilesList($src);

        foreach($MVCFolders as $folder) {
            if(in_array($folder, $whitelist) && $whitelist !== []) $this->recursiveCopy($src.$folder, $dst.$folder);
        }
    }

    function recursiveCopy(string $src, string $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ($file = readdir($dir))) {
            if($file != '.' && $file != '..') {
                if(is_dir("$src/$file")) {
                    $this->recursiveCopy("$src/$file", "$dst/$file");
                }
                else {
                    copy("$src/$file", "$dst/$file");
                }
            }
        }
        closedir($dir);
    }

    function copyFile(string $src, string $dst)
    {
        $src = $this->getProjectPath($src);
        $dst = $this->getProjectPath($dst);

        copy($src, $dst);
    }

    function gitMove(string $src, string $dst) : array
    {
        $output = [];

        $source = $this->getProjectPath($src);
        $destination = $this->getProjectPath($dst);

        exec ( "git mv $source $destination" , $output);

        return $output;
    }

    /**
     * Look if file exist
     * */
    public function fileExists(string $dest) : bool
    {
        if (!file_exists($dest)) {
            $this->output->writeln("✖ folder doesn't exist at $dest");
            return false;
        }
        return true;
    }

    /**
     * Backup a not versioned file.
     * */
    public function getProjectPath(string $file) : string
    {
        return "{$this->projectPath}/$file";
    }

    /**
     * Backup a not versioned file.
     *
     * TODO: Give the backed up files a unique name, using like the current time to avoid overriding them on multiple migrations
     * TODO: If we have to backup many files create another method that is gonna dispatch the array and call fileBackup
     * */
    public function fileBackup($file)
    {
        $dest = $this->getProjectPath($file);
        if (!copy($dest, $dest . '.backup')) {
            $this->output->writeln("✖ Failed to copy $file...");
            return;
        }

        $this->output->writeln("✔ Make copy of $file");
    }

    /**
     * Insert text in file create one if it doesn't exist
     */
    function putInFile(string $filePath, string $text, bool $append = false)
    {
        $filePath = $this->getProjectPath($filePath);

        $flags = 0;

        if($append) {
            $flags = FILE_APPEND;
        }

        try {
            if (!file_put_contents($filePath, $text, $flags) > 0) {
                $this->output->writeln("✖ Error while writing file");
            }
        } catch (\Exception $e) {
            $this->output->writeln("✖ Exception: $e");
        }

        $this->output->writeln("✔ Created file at $filePath");
    }

    function replaceInFile(string $filePath, array $rows, $simpleString = false) : bool
    {
        $filePath = $this->getProjectPath($filePath);

        $regexes = [];
        $replaces = [];

        foreach ($rows as $row) {
            $regex = $this->parseRegex($row[0]);

            $replace = $row[1];

            // If it's an anonymous function
            if($replace instanceof \Closure) {

                if (file_exists($filePath) === false || !is_writeable($filePath)) {
                    $this->output->writeln("✖ File $filePath does not exist or isn't writable");
                    return false;
                }

                $fileContent = file_get_contents($filePath);
                $count = 0;
                if (!$fileContent = preg_replace_callback($regex, $replace, $fileContent, -1, $count)) {
                    $this->output->writeln("✖ Error in preg_replace_callback()");
                    return false;
                }

                if($count == 0) {
                    $this->output->writeln("✖ Nothing to replace in $filePath");
                    return false;
                }

                if (!file_put_contents($filePath, $fileContent) > 0) {
                    $this->output->writeln("✖ Error while writing file");
                    return false;
                }

                return true;
            }

            $regexes[] = $regex;
            $replaces[] = $replace;
        }

        if (file_exists($filePath) === false || !is_writeable($filePath)) {
            $this->output->writeln("✖ File $filePath does not exist or isn't writable");
            return false;
        }

        try {
            $fileContent = file_get_contents($filePath);
            $count = 0;

            if($simpleString) {
                $fileContent = str_replace($regexes, $replaces, $fileContent, $count);
            }
            else {
                $fileContent = preg_replace($regexes, $replaces, $fileContent, -1, $count);
            }

            if($count == 0) {
                $this->output->writeln("✖ Nothing to replace in $filePath");
                return false;
            }

            if ($fileContent === null) {
                $this->output->writeln("✖ Error in preg_replace()");
                return false;
            }
            if (!file_put_contents($filePath, $fileContent) > 0) {
                $this->output->writeln("✖ Error while writing file");
                return false;
            }

            $this->output->writeln("✔ Migration on file $filePath done");

            return true;
        } catch (\Exception $e) {
            $this->output->writeln("✖ Exception: $e");
            return false;
        }
    }

    function extractFromFile(string $filePath, string $regex) : array
    {
        $filePath = $this->getProjectPath($filePath);

        if (file_exists($filePath) === false || !is_writeable($filePath)) {
            $this->output->writeln("✖ File $filePath does not exist or isn't writable");
        }

        $fileContent = file_get_contents($filePath);

        $return = [];

        preg_match($regex, $fileContent, $return);

        return $return;
    }

    /**
     * Replace string using a regex in every files in a folder
     */
    function replaceInFolder(string $folderPath, array $rows) : bool
    {
        $files = $this->getFilesList($folderPath);

        $replaced = false;

        foreach ($files as $file) {
            $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;

            if(is_dir($this->getProjectPath($folderPath) . DIRECTORY_SEPARATOR . $file)) {
                if($this->replaceInFolder($filePath, $rows)) {
                    $replaced = true;
                }
            } else {
                if($this->replaceInFile($filePath, $rows)) {
                    $replaced = true;
                }
            }
        }

        return $replaced;
    }

    function replaceRegex(array $matchs, array $subRegex)
    {
        $regex = $this->parseRegex($subRegex[0]);
        $replace = $subRegex[1];

        return preg_replace($regex, $replace, $matchs[1]);
    }

    function parseRegex($regex)
    {
        if(is_array($regex)) {
            $regex = $this->sprintf_array($this->regexEsc(array_shift($regex)), $regex);
        } else $regex = $this->regexEsc($regex);

        return $regex;
    }

    function sprintf_array($format, $arr)
    {
        return call_user_func_array('sprintf', array_merge((array)$format, $arr));
    }

    /**
     * Get a list of the files in a folder
     */
    function getFilesList(string $folderPath) : array
    {
        $folderPath = $this->getProjectPath($folderPath);
        return array_diff(scandir($folderPath), array('..', '.'));
    }

    /**
     * Esc regex special chars
     */
    function regexEsc(string $regex) :string
    {
        return '/'.preg_quote($regex, '/').'/';
    }

    function mkdir($dir) : bool
    {
        $folderPath = $this->getProjectPath($dir);

        if (!mkdir($folderPath, 0755, false)) {
            return false;
        }

        return true;
    }

    /**
     * Get the Container.
     *
     * @return Container
     */
    public function getContainer()
    {
        return $this->pimple;
    }

    /**
     * Returns a service contained in the application pimple or null if none is found with that name.
     *
     * This is a convenience method used to retrieve an element from the Application pimple without having to assign
     * the results of the getContainer() method in every call.
     *
     * @param string $name Name of the service.
     *
     * @see self::getContainer()
     *
     * @api
     *
     * @return mixed|null
     */
    public function getService($name)
    {
        return $this->pimple[$name];
    }
}