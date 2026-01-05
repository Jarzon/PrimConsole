<?php
use Phinx\Migration\AbstractMigration;

class **CLASS_NAME** extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('**TABLE**');
        $table
            ->addColumn('number', 'integer', ['default' => '0', 'after' => 'number'])
            ->addColumn('string', 'string', ['default' => '', 'after' => 'number'])
            ->addColumn('text', 'text', ['null' => true, 'after' => 'number'])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'after' => 'number'])
            ->addColumn('decimal', 'decimal', ['scale' => '4', 'precision' => '10', 'after' => 'number'])
            ->addColumn('status', 'integer', ['default' => '0', 'after' => 'number'])
            ->addColumn('user_id', 'integer', ['default' => '0', 'after' => 'number'])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'after' => 'number'])
            ->addColumn('updated', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'after' => 'number'])

            ->changeColumn('text', 'string', ['default' => '', 'limit' => 500])
            ->update();

        $this->query("UPDATE **TABLE** SET column = ? where column = ?");
    }

    public function down(): void
    {
        $table = $this->table('**TABLE**');
        $table
            ->removeColumn('description')
            ->changeColumn('text', 'string', ['limit' => 50])
            ->update();
    }
}