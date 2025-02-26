<?php

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/infra/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/infra/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'development' => [
                'adapter' => 'sqlite',
                'name' => 'infra/database',
            ],
        ],
        'version_order' => 'creation'
    ];
