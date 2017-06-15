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
                    'layout' => $basic,
                    'attribute_data' => json_encode(['material' => ['en' => 'Leather', 'sv' => 'Läder']])
                ],
                [
                    'name' => json_encode(['en' => 'Camber Shoes', 'sv' => 'Camber Skor']),
                    'layout' => $featured,
                    'attribute_data' => json_encode(['material' => ['en' => 'Suede']])
                ],
                [
                    'name' => json_encode(['en' => 'Cross over sandals', 'sv' => 'Korsa över sandaler']),
                    'layout' => $basic,
                    'attribute_data' => json_encode(['material' => ['en' => 'Suede']])
                ]
            ],
            'Bags' => [
                // Bags
                [
                    'name' => json_encode(['en' => 'Knot leather bag', 'sv' => 'Knot läderväska']),
                    'layout' => $basic,
                    'attribute_data' => json_encode(['material' => ['en' => 'Leather', 'sv' => 'Läder']])
                ],
                [
                    'name' => json_encode(['en' => 'Arizona bag', 'sv' => 'Arizona väska']),
                    'layout' => $featured,
                    'attribute_data' => json_encode(['material' => ['en' => 'Leather']])
                ],
                [
                    'name' => json_encode(['en' => 'Beet bag', 'sv' => 'Köttväska']),
                    'layout' => $basic,
                    'attribute_data' => json_encode(['material' => ['en' => 'Cotton', 'sv' => 'Bomull']])
                ]
            ],
            'Jewellery' => [
                [
                    'name' => json_encode(['en' => 'Mesh watch', 'sv' => 'Mesh klocka']),
                    'layout' => $basic,
                    'attribute_data' => json_encode(['material' => ['en' => 'Stainless Steel']])
                ],
                [
                    'name' => json_encode(['en' => '3 Square earrings', 'sv' => '3 kvadratiska örhängen']),
                    'layout' => $featured,
                    'attribute_data' => json_encode(['material' => ['en' => 'Silver']])
                ],
                [
                    'name' => json_encode(['en' => 'Bird Brooch', 'sv' => 'Fågelbrosch']),
                    'layout' => $basic,
                    'attribute_data' => json_encode(['material' => ['en' => 'Silver']])
                ]
            ],
            'House items' => [
                [
                    'name' => json_encode(['en' => 'Feather dreamcatcher', 'sv' => 'Fjäderdrömskådare']),
                    'layout' => $basic,
                    'attribute_data' => json_encode(['material' => ['en' => 'Leather, Feathers, Wool']])
                ],
                [
                    'name' => json_encode(['en' => 'Driftwood fish', 'sv' => 'Driftwood fisk']),
                    'layout' => $featured,
                    'attribute_data' => json_encode(['material' => ['en' => 'Wood']])
                ],
                [
                    'name' => json_encode(['en' => 'Mirror Candleholder', 'sv' => 'Spegel ljushållare']),
                    'layout' => $basic,
                    'attribute_data' => json_encode(['material' => ['en' => 'Glass, Metal']])
                ]
            ]
        ];

        $i = 1;
        foreach ($products as $family => $products) {
            $family = ProductFamily::find($i);
            foreach ($products as $data) {
                //
                $product = Product::create([
                    'name' => $data['name'],
                    'attribute_data' => $data['attribute_data']
                ]);

                $attribute = Attribute::first();

                $product->layout()->associate($data['layout']);
                $product->family()->associate($family);
                $product->attributes()->attach($attribute);

                foreach (json_decode($product->name, true) as $locale => $name) {
                    $product->route()->create([
                        'default' => true,
                        'slug' => str_slug($name),
                        'locale' => $locale
                    ]);
                }
                $product->save();
            }
            $i++;
        }
    }
}
