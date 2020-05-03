<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::updateOrCreate(['name' => 'manage-settings']);
        Permission::updateOrCreate(['name' => 'manage-bill']);

        $role = Role::updateOrCreate(['name' => 'admin']);
        $role->syncPermissions(['manage-settings']);

        $role = Role::updateOrCreate(['name' => 'user']);
        $role->syncPermissions(['manage-bill']);
    }
}
