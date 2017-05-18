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
            'name' => 'Amazon',
            'default' => true
        ]);
    }
}
