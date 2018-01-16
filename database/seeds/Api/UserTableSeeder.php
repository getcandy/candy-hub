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
        $language = app('api')->languages()->getDefaultRecord();

        $admin = User::create([
            'id' => 9999999,
            'title' => 'Mr',
            'first_name' => 'Alec',
            'last_name' => 'Ritson',
            'email' => 'hello@itsalec.co.uk',
            'password' => \Hash::make('password')
        ]);

        $admin->language()->associate($language);
        $admin->save();
        $admin->assignRole('admin');
        $admin->save();

        $customer = User::create([
            'id' => 9999998,
            'title' => 'Mr',
            'first_name' => 'Shaun',
            'last_name' => 'Rainer',
            'email' => 'shaun@neondigital.co.uk',
            'password' => \Hash::make('password')
        ]);

        $customer->language()->associate($language);
        $customer->save();
        $customer->assignRole('customer');
        $customer->save();
    }
}
