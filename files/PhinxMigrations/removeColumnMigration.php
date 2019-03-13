<?php
use Phinx\Migration\AbstractMigration;

class **CLASS_NAME** extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('**TABLE**');
        $table
            ->removeColumn('column')
            ->save();
    }

    public function down()
    {
        $table = $this->table('**TABLE**');
        $table
            ->addColumn('column', 'string', ['limit' => 50])
            ->save();
    }
}