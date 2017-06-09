<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Attributes\Models\Attribute;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $language = \GetCandy\Api\Languages\Models\Language::first()->id;

        $layout = \GetCandy\Api\Layouts\Models\Layout::create([
            'name' => 'Test layout',
            'handle' => 'test-layout'
        ])->id;
        $channel = \GetCandy\Api\Channels\Models\Channel::first()->id;

        factory(Product::class, (app('env') == 'testing' ? 3 : 5000))->create()->each(function ($product) use ($layout, $language, $channel) {
            $fake = \Faker\Factory::create();
            $atts = Attribute::inRandomOrder()->take($fake->numberBetween(0, 2))->get();
            $page = \GetCandy\Api\Pages\Models\Page::create([
                'language_id' => $language,
                'slug' => str_slug($product->name),
                'layout_id' => $layout,
                'channel_id' => $channel,
                'type' => 'product',
                'element_id' => $product->id,
                'element_type' => Product::class
            ]);
            $product->attributes()->attach($atts);
        });
    }
}
