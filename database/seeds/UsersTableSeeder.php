<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            array('email' => 'admin@gmail.com', 'password' => Hash::make('password123'), 'role' => 'admin'),
            array('email' => 'user@gmail.com', 'password' => Hash::make('password123'), 'role' => 'user'),
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
