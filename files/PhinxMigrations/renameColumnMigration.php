<?php
use Phinx\Migration\AbstractMigration;

class **CLASS_NAME** extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('**TABLE**');
        $table
            ->renameColumn('oldName', 'newName')
            ->update();
    }
}