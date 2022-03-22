<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => Role::ADMIN]);

        Role::create(['name' => Role::OWNER]);

        Permission::create(['name' => Permission::DELETE_ANY_NOTE]);

        $admin->givePermissionTo(Permission::DELETE_ANY_NOTE);
    }
}
