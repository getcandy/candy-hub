<?php

use Illuminate\Database\Seeder;

use GetCandy\Api\Collections\Models\Collection;
use GetCandy\Api\Attributes\Models\Attribute;

class CollectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $collections = [
            [
                'attribute_data' => [
                    'name' => [
                        'en' => 'Early Sale', 'sv' => 'Tidig försäljning'
                    ]
                ]
            ],
            [
                'attribute_data' => [
                    'name' => [
                        'en' => 'House'
                    ]
                ]
            ]
        ];

        $attributes = Attribute::get();

        foreach ($collections as $c) {
            $collection = Collection::create($c);
            foreach ($collection->attribute_data['name'] as $channel => $attr_data) {
                if ($channel == 'ecommerce') {
                    $collection->route()->create([
                        'default' => true,
                        'slug' => str_slug($attr_data['en']) . '-collection',
                        'locale' => 'en'
                    ]);
                }
            }
            foreach ($attributes as $att) {
                $collection->attributes()->attach($att);
            }
        }
    }
}
