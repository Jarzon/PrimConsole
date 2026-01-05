<?php
use Phinx\Migration\AbstractMigration;

class **CLASS_NAME** extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('**TABLE**');
        $table
            ->removeColumn('column')
            ->save();
    }

    public function down(): void
    {
        $table = $this->table('**TABLE**');
        $table
            ->addColumn('column', 'string', ['limit' => 50])
            ->save();
    }
}