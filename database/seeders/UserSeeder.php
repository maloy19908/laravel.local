<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::where('slug', 'admin')->first();
        $user1 = new User();
        $user1->name = 'Админ';
        $user1->email = 'superuser@mail.ru';
        $user1->password = bcrypt('qwerty');
        $user1->save();
        $user1->roles()->attach($admin);
    }
}
