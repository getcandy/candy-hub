<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Products\Models\Product;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \GetCandy\Api\Settings\Models\Setting::create([
            'name' => 'Products',
            'handle' => 'products',
            'content' => [
                'asset_source' => 'products',
                'transforms' => ['hero']
            ]
        ]);
    }
}
