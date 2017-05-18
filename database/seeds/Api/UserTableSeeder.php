<?php

use Illuminate\Database\Seeder;

use Faker\Factory;
use GetCandy\Api\Auth\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fake = Factory::create();
        User::create([
            'name' => 'Alec',
            'email' => 'alec@neondigital.co.uk',
            'password' => \Hash::make('password')
        ]);
    }
}
