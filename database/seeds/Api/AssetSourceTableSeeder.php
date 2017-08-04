<?php

use Illuminate\Database\Seeder;

class AssetSourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sources= [
            [
                'name' => 'Product images',
                'handle' => 'products',
                'disk' => 'public',
                'path' => 'products'
            ],
            [
                'name' => 'Channel images',
                'handle' => 'channels',
                'disk' => 'public'
            ]
        ];

        foreach ($sources as $source) {
            \GetCandy\Api\Assets\Models\AssetSource::create($source);
        }

        \GetCandy\Api\Assets\Models\Transform::create([
            'name' => 'Thumbnail',
            'handle' => 'thumbnail',
            'width' => 100,
            'height' => 100
        ]);

        \GetCandy\Api\Assets\Models\Transform::create([
            'name' => 'Hero',
            'handle' => 'hero',
            'mode' => 'crop',
            'width' => 800,
            'height' => 400
        ]);
    }
}
