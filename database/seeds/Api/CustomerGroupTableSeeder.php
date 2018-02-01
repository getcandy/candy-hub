<?php

use GetCandy\Api\Customers\Models\CustomerGroup;
use Illuminate\Database\Seeder;

class CustomerGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // CustomerGroup::forceCreate([
        //     'name' => 'Retail',
        //     'handle' => 'retail',
        //     'default' => true,
        //     'system' => true
        // ]);

        CustomerGroup::forceCreate([
            'name' => 'Guest',
            'handle' => 'guest',
            'default' => false,
            'system' => true
        ]);
    }
}
