<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'view users',
            'edit users',
            'delete users',
            'create users',
            'view roles',
            'edit roles',
            'delete roles',
            'create roles',
            'view permissions',
            'edit permissions',
            'delete permissions',
            'create permissions',
            'manage-empresa',
            'datos-empresa',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        $role = Role::firstOrCreate(['name' => 'admin']);
        $role->givePermissionTo($permissions);
    }
}
