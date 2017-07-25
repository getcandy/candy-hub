<?php

use GetCandy\Api\Customers\Models\CustomerGroup;
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
        $user = User::create([
            'name' => 'Alec',
            'email' => 'alec@neondigital.co.uk',
            'password' => \Hash::make('password')
        ]);

        $group = CustomerGroup::find(1);
        $user->groups()->attach($group);
    }
}
