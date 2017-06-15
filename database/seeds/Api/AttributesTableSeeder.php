<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Products\Models\Product;

class AttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fake = \Faker\Factory::create();

        $group = AttributeGroup::create([
            'name' => json_encode(['en' => 'General', 'sv' => 'Allmän']),
            'handle' => 'general',
            'position' => 1
        ]);

        Attribute::create([
            'name' => json_encode(['en' => 'Material', 'sv' => 'Väv']),
            'group_id' => $group->id,
            'handle' => 'material',
            'position' => 1
        ]);
    }
}
