<?php

use Faker\Factory;
use GetCandy\Api\Channels\Models\Channel;
use Illuminate\Database\Seeder;

class ChannelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Channel::create([
            'name' => 'Ecommerce',
            'handle' => 'ecommerce',
            'default' => true
        ]);
        Channel::create([
            'name' => 'Mobile',
            'handle' => 'mobile',
            'default' => false
        ]);
        Channel::create([
            'name' => 'Print',
            'handle' => 'print',
            'default' => false
        ]);
    }
}
