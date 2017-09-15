<?php

use Faker\Factory;
use GetCandy\Api\Categories\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fashion = Category::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'Fashion', 'sv' => 'Mode'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]
        ]);

        $fashion->routes()->create([
            'default' => true,
            'slug' => str_slug('Fashion'),
            'locale' => 'en'
        ]);

        $fashion->routes()->create([
            'default' => true,
            'slug' => str_slug('Mode'),
            'locale' => 'sv'
        ]);

        // Item 1

        $item = $fashion->children()->create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'Tops & Blouses', 'sv' => 'Toppar & Blusar'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]

        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Tops & Blouses'),
            'locale' => 'en'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Toppar & Blusar'),
            'locale' => 'sv'
        ]);

        // Item 2

        $item = $fashion->children()->create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'Knitwear', 'sv' => 'Stickat'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Knitwear'),
            'locale' => 'en'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Stickat'),
            'locale' => 'sv'
        ]);

        // Item 3

        $item = $fashion->children()->create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'Cardigans & Wraps'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Cardigans & Wraps'),
            'locale' => 'en'
        ]);

        // Item 4

        $acc = $fashion->children()->create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'Accessories', 'sv' => 'Tillbehör'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]
        ]);

        $acc->routes()->create([
            'default' => true,
            'slug' => str_slug('Accessories'),
            'locale' => 'en'
        ]);

        $acc->routes()->create([
            'default' => true,
            'slug' => str_slug('Tillbehör'),
            'locale' => 'sv'
        ]);

        // Item 5

        $thing = new Category();

        $thing->attribute_data = [
                'name' => [
                    'ecommerce' => [
                        'en' => 'Eye lashes', 'sv' => 'Ögonfransar'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]///////////////////////////////////////////////////////////////////////////////////////////
        ];

        $thing->appendToNode($acc)->save();

        $thing->routes()->create([
            'default' => true,
            'slug' => str_slug('Eye lashes'),
            'locale' => 'en'
        ]);

        $thing->routes()->create([
            'default' => true,
            'slug' => str_slug('Ögonfransar'),
            'locale' => 'sv'
        ]);

        // Item 6

        $item = $fashion->children()->create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'Coats & Jackets', 'sv' => 'Jackor'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Coats & Jackets'),
            'locale' => 'en'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Jackor'),
            'locale' => 'sv'
        ]);

        // Item 7

        $item = $fashion->children()->create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'Dresses & Skirts', 'sv' => 'Klänningar & Kjolar'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Dresses & Skirts'),
            'locale' => 'en'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Klänningar & Kjolar'),
            'locale' => 'sv'
        ]);

        // Item 8

        $item = Category::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'Shoes', 'sv' => 'Skor'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Shoes'),
            'locale' => 'en'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Skor'),
            'locale' => 'sv'
        ]);

        $item = Category::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'Bags', 'sv' => 'påsar'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Bags'),
            'locale' => 'en'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Påsar'),
            'locale' => 'sv'
        ]);

        $item = Category::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'Jewellery', 'sv' => 'Smycke'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Jewellery'),
            'locale' => 'en'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Smycke'),
            'locale' => 'sv'
        ]);

        $item = Category::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'House', 'sv' => 'Hem'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('House'),
            'locale' => 'en'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Hem'),
            'locale' => 'sv'
        ]);
    }
}
