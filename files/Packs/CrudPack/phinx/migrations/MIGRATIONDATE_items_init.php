<?php

use Phinx\Migration\AbstractMigration;

class ItemsInit extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('items');
        $table
            ->addColumn('name', 'string', ['limit' => 50])
            ->addColumn('description', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('status', 'integer', ['default' => '1'])
            ->addColumn('user_id', 'integer')
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
