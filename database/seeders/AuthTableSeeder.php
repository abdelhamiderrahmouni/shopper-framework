<?php

declare(strict_types=1);

namespace Shopper\Framework\Database\Seeders;

use Illuminate\Database\Seeder;
use Shopper\Framework\Database\Seeders\Auth\PermissionRoleTableSeeder;
use Shopper\Framework\Database\Seeders\Auth\PermissionsTableSeeder;
use Shopper\Framework\Database\Seeders\Auth\RolesTableSeeder;
use Shopper\Framework\Traits\Database\DisableForeignKeys;
use Shopper\Framework\Traits\Database\TruncateTable;

class AuthTableSeeder extends Seeder
{
    use DisableForeignKeys;
    use TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys();

        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        $this->truncateMultiple([
            config('permission.table_names.model_has_permissions'),
            config('permission.table_names.model_has_roles'),
            config('permission.table_names.role_has_permissions'),
            config('permission.table_names.permissions'),
            config('permission.table_names.roles'),
            'users',
        ]);

        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);

        $this->enableForeignKeys();
    }
}
