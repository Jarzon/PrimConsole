<?php
namespace Prim\Console;

use Prim\Console\Service\FileHelper;

/**
 * Example command for testing purposes.
 */
class PhinxCommand extends Command
{
    public function __construct(array $options, $input = null, $output = null)
    {
        parent::__construct($options, $input, $output);

        $this
            ->setName('phinx')
            ->setDescription('Create a phinx migration');
    }

    public function exec()
    {
        $migrationTypes = [
            1 => 'newTable',
            2 => 'addColumns',
            3 => 'changeColumn',
            4 => 'renameColumn',
            5 => 'removeColumn'
        ];

        $projectName = $this->options['project_name'];

        $dateTime = date('YmdHis');

        $this->output->writeLine("In what pack is the migration going?");
        $pack = $this->input->read();

        $pathToPack = "{$this->options['root']}src/{$pack}";

        if (!file_exists($pathToPack)) {
            $this->output->writeLine("✖ $pack doesn't exist");
            return;
        }

        $this->output->writeLine("What table is the migration applying to?");
        $table = $this->input->read();

        $this->output->writeLine("In a few words, how would you describe the migration?");
        $description = $this->input->read();

        $description = explode(' ', $description);

        $underscoreDescription = implode('_', $description);

        $camelCaseDescription = implode('', array_map('ucfirst', $description));

        $this->output->writeLine("What migration type do you want?");
        $this->output->writeLine("1) Create a new table");
        $this->output->writeLine("2) Add columns to existing table");
        $this->output->writeLine("3) Change table's columns");
        $this->output->writeLine("4) Rename columns");
        $this->output->writeLine("5) Remove columns");

        $migrationType = $this->input->read();

        $destinationFile = "{$pathToPack}/phinx/migrations/{$dateTime}_{$table}_{$underscoreDescription}.php";
        $migrationClassName = ucfirst($table) . $camelCaseDescription;

        FileHelper::copyFile(__DIR__ . "{$migrationTypes[$migrationType]}Migration.php", $destinationFile);

        FileHelper::replaceInFile($destinationFile, [
            ['**TABLE**', $table],
            ['**CLASS_NAME**', $migrationClassName]
        ]);

        return;
    }
}
