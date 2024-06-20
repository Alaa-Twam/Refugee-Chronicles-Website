<?php

namespace Pearls\User\database\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Pearls\User\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('users')->delete();

        $superuser_id = \DB::table('users')->insertGetId([
            'first_name' => 'Super',
            'last_name' => 'User',
            'username' => 'superuser',
            'email' => 'superuser@bazaard.dev',
            'password' => bcrypt('123456'),
            'type' => 'admin',
            'status' => 'active',
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $admin_id = \DB::table('users')->insertGetId([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'username' => 'admin',
            'email' => 'admin@bazaard.dev',
            'password' => bcrypt('123456'),
            'type' => 'admin',
            'status' => 'active',
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $superUserRole = Role::whereName('Super User')->first();

        if ($superUserRole) {
            $superUserRole->users()->attach($superuser_id);
        }

        $adminUserRole = Role::whereName('Admin')->first();

        if ($adminUserRole) {
            $adminUserRole->users()->attach($admin_id);
        }

    }
}
