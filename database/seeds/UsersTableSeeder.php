<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                'facebook_id' => '',
                'facebook_token' => '',
                'name' => '',
                'email' => '',
                'password' => bcrypt(''),
            ]
        );
    }
}
