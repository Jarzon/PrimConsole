<?php
use Phinx\Migration\AbstractMigration;

class **CLASS_NAME** extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('**TABLE**');
        $table
            ->addColumn('number', 'integer', ['default' => '0'])
            ->addColumn('string', 'string', ['default' => '', 'after' => 'number'])
            ->addColumn('text', 'text', ['null' => true])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('decimal', 'decimal', ['scale' => '4', 'precision' => '10'])
            ->addColumn('status', 'integer', ['default' => '0'])
            ->addColumn('user_id', 'integer', ['default' => '0'])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])

            ->addIndex(['number'])

            ->create();
    }
}