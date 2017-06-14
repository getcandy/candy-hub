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
                    'name' => json_encode(['en' => 'Black Bamboosh', 'sv' => 'Svart Bamboosh']),
                    'layout' => $basic
                ],
                [
                    'name' => json_encode(['en' => 'Camber Shoes', 'sv' => 'Camber Skor']),
                    'layout' => $featured
                ],
                [
                    'name' => json_encode(['en' => 'Cross over sandals', 'sv' => 'Korsa över sandaler']),
                    'layout' => $basic
                ]
            ],
            'Bags' => [
                // Bags
                [
                    'name' => json_encode(['en' => 'Knot leather bag', 'sv' => 'Knot läderväska']),
                    'layout' => $basic
                ],
                [
                    'name' => json_encode(['en' => 'Arizona bag', 'sv' => 'Arizona väska']),
                    'layout' => $featured
                ],
                [
                    'name' => json_encode(['en' => 'Beet bag', 'sv' => 'Köttväska']),
                    'layout' => $basic
                ]
            ],
            'Jewellery' => [
                [
                    'name' => json_encode(['en' => 'Mesh watch', 'sv' => 'Mesh klocka']),
                    'layout' => $basic
                ],
                [
                    'name' => json_encode(['en' => '3 Square earrings', 'sv' => '3 kvadratiska örhängen']),
                    'layout' => $featured
                ],
                [
                    'name' => json_encode(['en' => 'Bird Brooch', 'sv' => 'Fågelbrosch']),
                    'layout' => $basic
                ]
            ],
            'House items' => [
                [
                    'name' => json_encode(['en' => 'Feather dreamcatcher', 'sv' => 'Fjäderdrömskådare']),
                    'layout' => $basic
                ],
                [
                    'name' => json_encode(['en' => 'Driftwood fish', 'sv' => 'Driftwood fisk']),
                    'layout' => $featured
                ],
                [
                    'name' => json_encode(['en' => 'Mirror Candleholder', 'sv' => 'Spegel ljushållare']),
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

                foreach (json_decode($product->name, true) as $locale => $name) {
                    $product->route()->create([
                        'default' => true,
                        'slug' => str_slug($name),
                        'locale' => $locale
                    ]);
                }
                $product->save();
            }
        }
    }
}
