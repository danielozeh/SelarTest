<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            'id' => 1,
            'first_name' => 'Daniel',
            'last_name' => 'Ozeh',
            'email' => 'hello@danielozeh.com.ng',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'user_level' => 1,
            'is_verified' => 1,
            'verification_code' => Str::random(10),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $user_profiles = [
            'id' => 1,
            'user_id' => 1
        ];

        DB::table('users')->insert($users);
        DB::table('user_profiles')->insert($user_profiles);
    }
}
