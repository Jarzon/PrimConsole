<?php
use Phinx\Migration\AbstractMigration;

class **CLASS_NAME** extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('**TABLE**');
        $table
            ->changeColumn('description', 'string', ['default' => '', 'limit' => 500])
            ->update();
    }

        public function down()
    {
        $table = $this->table('**TABLE**');
        $table
            ->changeColumn('description', 'string', ['limit' => 50])
            ->update();
    }
}