<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'Администратор';
        $role->slug = 'admin';
        $role->save();

        $role = new Role();
        $role->name = 'Клиент';
        $role->slug = 'client';
        $role->save();
    }
}
