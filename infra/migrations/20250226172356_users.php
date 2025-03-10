<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Users extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users');

        $table
            ->addColumn('name', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('email', 'string', ['null' => false])
            ->addColumn('password', 'string', ['null' => false])
            ->addColumn('is_active', 'integer', ['default' => 1])
            ->addTimestamps()
            ->addColumn('deleted_at', 'datetime', ['default' => null])
            ->addColumn('restored_at', 'datetime', ['default' => null])
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}
