<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use GetCandy\Api\Models\Product;
use GetCandy\Api\Models\Attribute;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 3; $i++) {
            factory(Product::class, (app('env') == 'testing' ? 2 : 10))->create()->each(function ($product) {
                $fake = \Faker\Factory::create();
                $atts = Attribute::inRandomOrder()->take($fake->numberBetween(0, 2))->get();
                $product->attributes()->attach($atts);
            });
        }
    }
}
