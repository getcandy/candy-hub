<?php

use GetCandy\Api\Customers\Models\CustomerGroup;
use Illuminate\Database\Seeder;
use Faker\Factory;
use GetCandy\Api\Auth\Models\User;
use GetCandy\Api\Languages\Models\Language;

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

        $language = app('api')->languages()->getDefaultRecord();


        $user->language()->associate($language);
        $user->save();

        $group = CustomerGroup::find(1);
        $user->groups()->attach($group);
    }
}
