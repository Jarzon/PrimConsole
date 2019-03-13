<?php
use Phinx\Migration\AbstractMigration;

class **CLASS_NAME** extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('**TABLE**');
        $table
            ->addColumn('number', 'integer', ['default' => '0', 'after' => 'number'])
            ->addColumn('text', 'text', ['null' => true, 'after' => 'number'])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'after' => 'number'])
            ->addColumn('decimal', 'decimal', ['scale' => '4', 'precision' => '10', 'after' => 'number'])
            ->addColumn('status', 'integer', ['default' => '0', 'after' => 'number'])
            ->addColumn('user_id', 'integer', ['default' => '0', 'after' => 'number'])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'after' => 'number'])
            ->addColumn('updated', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'after' => 'number'])

            ->update();
    }
}