<?php

use Illuminate\Database\Seeder;

use GetCandy\Api\Collections\Models\Collection;

class CollectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Collection::create([
            'name' => 'Early Sale'
        ]);
        Collection::create([
            'name' => 'House'
        ]);
    }
}
