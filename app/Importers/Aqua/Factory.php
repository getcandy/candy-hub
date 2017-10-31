<?php

namespace GetCandy\Importers\Aqua;

use DB;
use GetCandy\Importers\BaseImporter;
use GetCandy\Importers\Aqua\Models\UserGroups\UserGroup;
use GetCandy\Importers\Aqua\Models\Products\Product;
use GetCandy\Importers\Aqua\Models\Categories\Category;
use GetCandy\Importers\Aqua\Models\Channel;

class Factory extends BaseImporter
{
    protected $database;
    protected $decorator;

    public function __construct(Decorator $decorator)
    {
        $this->database = DB::connection('aquaspa');
        $this->decorator = $decorator;
    }

    /**
     * Get the products
     * @return array
     */
    public function getProducts()
    {
        $products = Product::with(['images', 'channel', 'categories'])->get();

        return $products->toArray();

        // return $products;
    }

    public function getCategories()
    {
        $categories = Category::parents()->with(['children', 'children.children'])->get()->toArray();
        return $categories;
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
        return Channel::all()->toArray();
    }

    /**
     * Get the customer groups
     * @return array
     */
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
