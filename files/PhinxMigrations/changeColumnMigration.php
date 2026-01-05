<?php
use Phinx\Migration\AbstractMigration;

class **CLASS_NAME** extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('**TABLE**');
        $table
            ->changeColumn('description', 'string', ['default' => '', 'limit' => 500])
            ->update();
    }

    public function down(): void
    {
        $table = $this->table('**TABLE**');
        $table
            ->changeColumn('description', 'string', ['limit' => 50])
            ->update();
    }
}