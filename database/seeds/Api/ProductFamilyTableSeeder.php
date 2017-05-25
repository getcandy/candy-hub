<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use GetCandy\Api\Models\ProductFamily;
use GetCandy\Api\Models\Attribute;

class ProductFamilyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ProductFamily::class, 16)->create()->each(function ($family) {
            $fake = \Faker\Factory::create();
            $atts = Attribute::inRandomOrder()->take($fake->numberBetween(1, (Attribute::count() / $fake->numberBetween(2, 4))))->get();
            $family->attributes()->attach($atts);
        });
    }
}
