<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Products\Models\ProductFamily;
use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Routes\Models\Route;

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

        $basic = \GetCandy\Api\Layouts\Models\Layout::create([
            'name' => 'Basic product',
            'handle' => 'basic-product'
        ])->id;

        $featured = \GetCandy\Api\Layouts\Models\Layout::create([
            'name' => 'Featured product',
            'handle' => 'featured-product'
        ])->id;

        $channel = \GetCandy\Api\Channels\Models\Channel::first()->id;

        $products = [
            // Boots
            'Shoes' => [
                [
                    'name' => json_encode(['en' => 'Black Bamboosh']),
                    'layout' => $basic
                ],
                [
                    'name' => json_encode(['en' => 'Camber Shoes']),
                    'layout' => $featured
                ],
                [
                    'name' => json_encode(['en' => 'Cross over sandals']),
                    'layout' => $basic
                ]
            ],
            'Bags' => [
                // Bags
                [
                    'name' => json_encode(['en' => 'Knot leather bag']),
                    'layout' => $basic
                ],
                [
                    'name' => json_encode(['en' => 'Arizona bag']),
                    'layout' => $featured
                ],
                [
                    'name' => json_encode(['en' => 'Beet bag']),
                    'layout' => $basic
                ]
            ],
            'Jewellery' => [
                [
                    'name' => json_encode(['en' => 'Mesh watch']),
                    'layout' => $basic
                ],
                [
                    'name' => json_encode(['en' => '3 Square earrings']),
                    'layout' => $featured
                ],
                [
                    'name' => json_encode(['en' => 'Bird Brooch']),
                    'layout' => $basic
                ]
            ],
            'House items' => [
                [
                    'name' => json_encode(['en' => 'Feather dreamcatcher']),
                    'layout' => $basic
                ],
                [
                    'name' => json_encode(['en' => 'Driftwood fish']),
                    'layout' => $featured
                ],
                [
                    'name' => json_encode(['en' => 'Mirror Candleholder']),
                    'layout' => $basic
                ]
            ]
        ];

        foreach ($products as $family => $products) {
            $family = ProductFamily::where('name', '=', $family)->first();
            foreach ($products as $data) {
                $product = Product::create([
                    'name' => $data['name']
                ]);
                $product->layout()->associate($data['layout']);
                $product->family()->associate($family);
                $product->route()->create([
                    'slug' => str_slug(json_decode($product->name, true)['en'])
                ]);
                $product->save();
            }
        }
    }
}
