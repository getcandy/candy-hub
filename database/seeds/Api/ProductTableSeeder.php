<?php

use GetCandy\Api\Customers\Models\CustomerGroup;
use Illuminate\Database\Seeder;
use Faker\Factory;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Products\Models\ProductFamily;
use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Products\Models\ProductVariant;

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


        $products = [
            // Boots
            'Shoes' => [
                [
                    'layout' => $basic,
                    'attribute_data' => [
                        'name' => [
                            'ecommerce' => [
                                'gb' => 'Black Bamboosh',
                                'se' => 'Svart Bamboosh'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ],
                        'material' => [
                            'ecommerce' => [
                                'gb' => 'Leather',
                                'se' => 'Läder'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ],
                        'description' => [
                            'ecommerce' => [
                                'gb' => 'Moroccan babouche made from butter-soft black leather with slim rubber soles. Indoor wear recommended.',
                                'se' => 'Marockansk babouche tillverkad av smörmjuk svart läder med smala gummisulor. Inomslitage rekommenderas'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ]
                    ]
                ],
                [
                    'layout' => $featured,
                    'attribute_data' => [
                        'name' => [
                            'ecommerce' => [
                                'gb' => 'Camber Shoes',
                                'se' => 'Camber Skor',
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ],
                        'material' => [
                            'ecommerce' => [
                                'gb' => 'Suede',
                                'se' => 'Mockaskinn'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ],
                        'description' => [
                            'ecommerce' => [
                                'gb' => 'Slip-on soft pink suede brogues toughened up with comfortable crepe soles and leather lining. Penelope Chilvers.',
                                'se' => 'Slip-on mjuka rosa suede brogues härdade med bekväma crepe sulor och läderfodral. Penelope Chilvers.'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ]
                    ]
                ],
                [
                    'layout' => $basic,
                    'attribute_data' => [
                        'name' => [
                            'ecommerce' => [
                                'gb' => 'Cross over sandals',
                                'se' => 'Korsa över sandaler'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ],
                        'material' => [
                            'ecommerce' => [
                                'gb' => 'Suede',
                                'se' => 'Mockaskinn'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ],
                        'description' => [
                            'ecommerce' => [
                                'gb' => 'Sleek slides with wide cross-over straps made from chunky-ribbed leather and suede. With padded leather footbed and grooved rubber soles.',
                                'se' => 'Snygga glidbanor med breda överkantsremmar av chunky-ribbet läder och mocka. Med vadderade läderfotbäddar och räfflade gummisulor.'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ]
                    ]
                ]
            ],
            'Bags' => [
                // Bags
                [
                    'layout' => $basic,
                    'attribute_data' => [
                        'name' => [
                            'ecommerce' => [
                                'gb' => 'Knot leather bag',
                                'se' => 'Knot läderväska'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ],
                        'material' => [
                            'ecommerce' => [
                                'gb' => 'Leather',
                                'se' => 'Läder'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ],
                        'description' => [
                            'ecommerce' => [
                                'gb' => 'Sleek suede bag with chunky rolled leather strap and exaggerated knots at either end. Closes with zip across the top and has interior pockets.',
                                'se' => ''
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ]
                    ]
                ],
                [
                    'layout' => $featured,
                    'attribute_data' => [
                        'name' => [
                            'ecommerce' => [
                                'gb' => 'Arizona bag',
                                'se' => 'Arizona väska'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ],
                        'material' => [
                            'ecommerce' => [
                                'gb' => 'Leather',
                                'se' => 'Läder'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ],
                        'description' => [
                            'ecommerce' => [
                                'gb' => 'Artisan crafted cross body bag made from waxy umber leather with a wide buckled strap and deep front flap. Lined in twill with interior pockets. ',
                                'se' => ''
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ]
                    ]
                ],
                [
                    'layout' => $basic,
                    'attribute_data' => [
                        'name' => [
                            'ecommerce' => [
                                'gb' => 'Beet bag',
                                'se' => 'Köttväska'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ],
                        'material' => [
                            'ecommerce' => [
                                'gb' => 'Cotton',
                                'se' => 'Bomull'
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ],
                        'description' => [
                            'ecommerce' => [
                                'gb' => 'Capacious beach bag with room for just about anything. Cut from geometric-print cotton with fully lined interior and single zip pocket. Tie closure.',
                                'se' => ''
                            ],
                            'mobile' => [
                                'gb' => '',
                                'se' => ''
                            ],
                            'print' => [
                                'gb' => '',
                                'se' => ''
                            ]
                        ]
                    ]
                ]
            ],
            // 'Jewellery' => [
            //     [
            //         'name' => ['gb' => 'Mesh watch', 'se' => 'Mesh klocka'],
            //         'layout' => $basic,
            //         'attribute_data' => ['material' => ['gb' => 'Stainless Steel']]
            //     ],
            //     [
            //         'name' => ['gb' => '3 Square earrings', 'se' => '3 kvadratiska örhängen'],
            //         'layout' => $featured,
            //         'attribute_data' => ['material' => ['gb' => 'Silver']]
            //     ],
            //     [
            //         'name' => ['gb' => 'Bird Brooch', 'se' => 'Fågelbrosch'],
            //         'layout' => $basic,
            //         'attribute_data' => ['material' => ['gb' => 'Silver']]
            //     ]
            // ],
            // 'House items' => [
            //     [
            //         'name' => ['gb' => 'Feather dreamcatcher', 'se' => 'Fjäderdrömskådare'],
            //         'layout' => $basic,
            //         'attribute_data' => ['material' => ['gb' => 'Leather, Feathers, Wool']]
            //     ],
            //     [
            //         'name' => ['gb' => 'Driftwood fish', 'se' => 'Driftwood fisk'],
            //         'layout' => $featured,
            //         'attribute_data' => ['material' => ['gb' => 'Wood']]
            //     ],
            //     [
            //         'name' => ['gb' => 'Mirror Candleholder', 'se' => 'Spegel ljushållare'],
            //         'layout' => $basic,
            //         'attribute_data' => ['material' => ['gb' => 'Glass, Metal']]
            //     ]
            // ]
        ];
        $i = 1;
        $attributes = Attribute::get();
        foreach ($products as $family => $products) {
            $family = ProductFamily::find($i);
            foreach ($products as $data) {
                $product = Product::create([
                    'attribute_data' => $data['attribute_data']
                ]);

                $productVariant = new ProductVariant;

                $productVariant->options = [];
                $productVariant->sku = str_random(8);
                $productVariant->stock = 1;
                $productVariant->price = 40;

                $product->customerGroups()->sync([
                    1 => ['visible' => true, 'purchasable' => true]
                ]);

                foreach ($attributes as $att) {
                    $product->attributes()->attach($att);
                }

                $product->layout()->associate($data['layout']);
                $product->family()->associate($family);

                foreach ($product->attribute_data['name'] as $channel => $data) {
                    if ($channel == 'ecommerce') {
                        $product->route()->create([
                            'default' => true,
                            'slug' => str_slug($data['gb']),
                            'locale' => app()->getLocale()
                        ]);
                    }
                }
                $product->save();

                $product->variants()->save($productVariant);
            }
            $i++;
        }
    }
}
