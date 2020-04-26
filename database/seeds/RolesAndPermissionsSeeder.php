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
        Permission::updateOrCreate(['name' => 'manage_settings']);
        Permission::updateOrCreate(['name' => 'manage_bill']);

        $role = Role::updateOrCreate(['name' => 'admin']);
        $role->syncPermissions(['manage_settings']);

        $role = Role::updateOrCreate(['name' => 'user']);
        $role->syncPermissions(['manage_bill']);
    }
}
