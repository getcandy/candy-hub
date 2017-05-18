<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Attributes\Models\AttributeGroup;

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

        for ($i=1; $i < 7; $i++) {
            $name = $fake->unique()->domainWord;
            $model = AttributeGroup::create([
                'name' => ucfirst($name),
                'handle' => str_slug($name),
                'position' => $i
            ]);
        }

        $fake = \Faker\Factory::create();

        foreach (AttributeGroup::all() as $group) {
            for ($i=1; $i < $fake->numberBetween(2, 7); $i++) {
                $name = $fake->unique()->name;
                $attribute = Attribute::create([
                    'name' => ucfirst($name),
                    'group_id' => $group->id,
                    'handle' => str_slug($name),
                    'position' => $i
                ]);
            }
        }
    }
}
