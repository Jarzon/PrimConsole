<?php
namespace Prim\Console;

use Prim\Console\Service\FileHelper;

class PhinxCommand extends Command
{
    public function __construct(array $options, Input $input = null, Output $output = null)
    {
        parent::__construct($options, $input, $output);

        $this
            ->setName('phinx')
            ->setDescription('Create a phinx migration');
    }

    public function exec()
    {
        $migrationTypes = [
            'newTable',
            'addColumns',
            'changeColumn',
            'renameColumn',
            'removeColumn'
        ];

        $dateTime = date('YmdHis');

        $this->output->writeLine("In what pack is the migration going?");

        $pack = $name = $this->input->read();

        if(strpos($pack, 'Pack') === false) {
            $pack = "{$pack}Pack";
        } else {
            $name = str_replace('Pack', '', $name);
        }

        $pack = ucfirst($pack);
        $name = lcfirst($name . 's');

        $pathToPack = "{$this->options['root']}src/{$pack}";

        if (!file_exists($pathToPack)) {
            $this->output->writeLine("✖ $pack doesn't exist");
            return;
        }

        $this->output->writeLine("What table is the migration applying to? [$name]");
        $table = $this->input->read();

        if($table === '') {
            $table = $name;
        }

        $this->output->writeLine("In a few words, how would you describe the migration? [create]");
        $description = $this->input->read();

        if($description === '') {
            $description = 'create';
        }

        $descriptionWords = explode(' ', $description);

        $underscoreDescription = implode('_', $descriptionWords);

        $camelCaseDescription = implode('', array_map('ucfirst', $descriptionWords));

        $this->output->writeLine("What migration type do you want?");
        $actions = [
            'create' => "Create a new table",
            'add' => "Add columns to existing table",
            'change' => "Change table's columns",
            'rename' => "Rename columns",
            'remove' => "Remove columns"
        ];

        $defaultAction = null;
        $n = 1;
        foreach ($actions as $index => $action) {
            $line = "$n) ";

            if(strpos($description, $index) !== false) {
                $defaultAction = $n;
                $line .= "[$action]";
            } else {
                $line .= "$action";
            }

            $this->output->writeLine($line);
            $n++;
        }

        $migrationType = $this->input->read();

        if($migrationType === '') {
            $migrationType = $defaultAction ?? 1;
        }

        $migrationType -= 1;


        $destinationFile = "{$pathToPack}/phinx/";
        $migrationClassName = ucfirst($table) . $camelCaseDescription;

        if(!file_exists($destinationFile)) {
            FileHelper::mkdir($destinationFile);
        }

        $destinationFile .= "migrations/";

        if(!file_exists($destinationFile)) {
            FileHelper::mkdir($destinationFile);
        }

        $destinationFile .= "{$dateTime}_{$table}_{$underscoreDescription}.php";

        FileHelper::copyFile(dirname(__DIR__ ). "/files/PhinxMigrations/{$migrationTypes[$migrationType]}Migration.php", $destinationFile);

        FileHelper::replaceInFile($destinationFile, [
            ['**TABLE**', $table],
            ['**CLASS_NAME**', $migrationClassName]
        ]);

        return;
    }
}
