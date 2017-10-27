<?php

namespace GetCandy\Importers\Aqua;

use DB;
use GetCandy\Importers\BaseImporter;
use GetCandy\Importers\Aqua\Models\UserGroups\UserGroup;
use GetCandy\Importers\Aqua\Models\Products\Product;

class Factory extends BaseImporter
{
    protected $database;

    public function __construct()
    {
        $this->database = DB::connection('aquaspa');
    }

    /**
     * Get the products
     * @return array
     */
    public function getProducts()
    {
        $products = Product::with(['descriptions', 'images', 'images.ref'])->get();

        return $products->map(function ($product) {
            $name = [];
            $newName = null;
            $desc = [];
            $newDesc = null;
            $urls = [];
            $images = [];

            foreach ($product->descriptions as $description) {
                // Name
                if (str_slug($newName) == str_slug($description->product)) {
                    $name[$description->lang_code] = null;
                } else {
                    $name[$description->lang_code] = $description->product;
                    $urls[$description->lang_code] = str_slug($description->product);
                }
                $newName = $description->product;

                // Description
                if ($newDesc == $description->full_description) {
                    $desc[$description->lang_code] = null;
                } else {
                    $desc[$description->lang_code] = $description->full_description;
                }
                $newDesc = $description->full_description;
            }

            foreach ($product->images as $image) {
                if ($image->ref) {
                    $images[] = [
                        'url' => url('/aqua_assets/' . $image->ref->image_path),
                        'mime_type' => 'external'
                    ];
                }
            }

            return [
                'name' => $name,
                'family_id' => \GetCandy\Api\Products\Models\ProductFamily::first()->encodedId(),
                'description' => $desc,
                'url' => $urls,
                'stock' => $product->amount,
                'sku' => $product->product_code,
                'price' => $product->list_price,
                'images' => $images
            ];
        });

        return $products;
    }

    public function getProductFamilies()
    {
        return [
            [
                'attributes' => [
                    'name' => [
                        'uk' => [
                            'en' => 'Stock',
                            'fr' => null
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Get the channels
     * @return array
     */
    public function getChannels()
    {
        return [
            ['name' => 'UK', 'handle' => 'uk', 'default' => true],
            ['name' => 'Europe', 'handle' => 'europe', 'default' => false]
        ];
    }

    public function getCustomerGroups()
    {
        $groups = UserGroup::with('descriptions')->get();
        return $groups->map(function ($group) {
            $name = $group->descriptions->first()->usergroup;
            return [
                'name' => $name,
                'handle' => str_slug($name),
                'system' => $name == 'Retail' ? true : false,
                'default' => $name == 'Retail' ? true : false
            ];
        });
    }
}
