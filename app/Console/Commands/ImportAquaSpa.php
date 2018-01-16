<?php

namespace GetCandy\Console\Commands;

use DB;
use Illuminate\Console\Command;
use GetCandy\Api\Categories\Models\Category;
use GetCandy\Api\Products\Models\Product;

class ImportAquaSpa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import {driver}';

    protected $categories = [];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $importer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('migrate:refresh', [
            '--seed' => true
        ]);
        $driver = $this->argument('driver');
        $this->importer = app($driver . '.importer');

        \Auth::loginUsingId(9999999);

        $this->importChannels();
        $this->importCustomerGroups();
        $this->importShippingZones();
        $this->importShippingMethods();
        $this->importProductFamilies();
        $this->importCategories();
        $this->importDiscounts();
        $this->importUsers();
        $this->importOrders();
        $this->importProducts();

        $this->info('Done!');
    }

    protected function importDiscounts()
    {
        $discounts = $this->importer->getDiscounts();
        
        foreach ($discounts as $discount) {
            $model = app('api')->discounts()->create($discount);
            // dd($discount);
        }
        $this->info('');
    }
    /**
     * Import shipping methods
     *
     * @return void
     */
    protected function importShippingMethods()
    {
        $this->info('Importing Shipping Methods');
        $rates = $this->importer->getShippingRates();

        foreach ($rates as $rate) {
            // $price = [];
            $method = $rate->method->toArray();

            // $price['customer_groups'] = ;
            if ($rate->destination && $rate->destination->destination_id == 9) {
                $zones = [app('api')->shippingZones()->getByName('United Kingdom')->encodedId()];
            } else {
                $zones = [app('api')->shippingZones()->getByName('Europe')->encodedId()];
            }
            $shippingMethod = app('api')->shippingMethods()->create($method);
            app('api')->shippingMethods()->updateZones($shippingMethod->encodedId(), ['zones' => $zones]);

            $tiers = unserialize($rate->rate_value);

            foreach ($tiers as $row) {
                foreach ($row as $item) {
                    $price = [
                        'currency_id' => app('api')->currencies()->getDefaultRecord()->encodedId(),
                        'rate' => $item['value'],
                        'min_basket' => isset($item['amount']) ? $item['amount'] : 0,
                        'min_weight' => $rate->method->min_weight,
                        'max_weight' => $rate->method->max_weight,
                        'customer_groups' => $method['customer_groups']
                    ];
                    app('api')->shippingPrices()->create($shippingMethod->encodedId(), $price);
                }
            }
        }
        $this->info('');
    }

    /**
     * Import orders
     *
     * @return void
     */
    protected function importOrders()
    {
        $this->info('Importing Orders');
        $orders = $this->importer->getOrders();

        $bar = $this->output->createProgressBar(count($orders));

        foreach ($orders as $order) {
            \DB::table('orders')->insert([
                $order->toArray()
            ]);

            foreach ($order->lines as $line) {
                \DB::table('order_lines')->insert([
                    $line->toArray()
                ]);
            }
            $bar->advance();
        }
        $bar->finish();
        $this->info('');
    }

    /**
     * Import users
     *
     * @return void
     */
    protected function importUsers()
    {
        $this->info('Importing Users');
        $users = $this->importer->getUsers();

        $bar = $this->output->createProgressBar(count($users));

        foreach ($users as $user) {
            $model = app('api')->users()->create($user->toArray());

            foreach ($user->profiles as $addresses) {
                foreach ($addresses->toArray() as $type => $data) {
                    app('api')->addresses()->addAddress($model, $data, $type);
                }
            }

            // dd($model);
            $bar->advance();
        }
        // dd('end');
        $bar->finish();
        $this->info('');
    }

    /**
     * Import categories
     *
     * @return void
     */
    protected function importCategories()
    {
        $this->info('Importing Categories');
        $categories = $this->importer->getCategories();
        $bar = $this->output->createProgressBar(count($categories));

        foreach ($categories as $category) {
            // dd($category);
            $newCat = app('api')->categories()->create($category);

            foreach ($category['children'] as $index => $child) {
                $child['parent'] = [
                    'id' => $newCat->encodedId()
                ];
                app('api')->categories()->create($child);
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->info('');
    }

    /**
     * Import channels
     *
     * @return void
     */
    protected function importChannels()
    {
        $this->info('Importing Channels');
        $channels = $this->importer->getChannels();
        $bar = $this->output->createProgressBar(count($channels));

        foreach ($channels as $channel) {
            app('api')->channels()->create($channel);
            $bar->advance();
        }

        $bar->finish();
        $this->info('');
    }

    /**
     * Import product families
     *
     * @return void
     */
    public function importProductFamilies()
    {
        $this->info('Importing Product Families');
        $families = $this->importer->getProductFamilies();
        $bar = $this->output->createProgressBar(count($families));

        foreach ($families as $family) {
            app('api')->productFamilies()->create($family['attributes']);
            $bar->advance();
        }

        $bar->finish();
        $this->info('');
    }

    /**
     * Import shipping zones
     *
     * @return void
     */
    public function importShippingZones()
    {
        $this->info('Importing Shipping Zones');
        $zones = $this->importer->getShippingZones();
        $bar = $this->output->createProgressBar(count($zones));
        foreach ($zones as $zone) {
            app('api')->shippingZones()->create($zone);
            $bar->advance();
        }
        $bar->finish();
    }

    /**
     * Import products
     *
     * @return void
     */
    public function importProducts()
    {
        $this->info('Importing Products');
        $products = $this->importer->getProducts();

        $bar = $this->output->createProgressBar(count($products));

        foreach ($products as $product) {
            // First set up product option data...
            $product['option_data'] = $this->mapOptionData($product['options']);

            // No matter what, just create a basic product...
            $model = app('api')->products()->create($product);

            // Upload assets pretty much instantly.
            foreach ($product['images'] as $image) {
                app('api')->assets()->upload($image, $model);
            }

            foreach ($product['prices'] as $index => $price) {
                $product['prices'][$index]['tax_id'] = $product['tax_id'];
            }
            
            // This is seperated cause we wanna do two different things...
            if (count($product['options'])) {
                $variants = [];
                foreach ($product['options'] as $index => $option) {
                    if (!count($option['description'])) {
                        continue;
                    }
                    $name = str_slug($option['description'][0]['option_name']);
                    foreach ($option['variants'] as $vIndex => $variant) {
                        foreach ($variant['description'] as $vDesc) {
                            $sku = str_slug($product['sku']) . '-' . str_slug($vDesc['variant_name']);
                            $data = [
                                'sku' => $sku,
                                'price' => $product['price'],
                                'inventory' => $product['stock'],
                                'tax_id' => $product['tax_id'],
                                'tiers' => $product['tiers'],
                                'pricing' => $product['prices'],
                                'weight' => [
                                    'unit' => 'lb',
                                    'value' => $product['weight']
                                ],
                                'options' => [
                                    $name => [
                                        $vDesc['lang_code'] => $vDesc['variant_name']
                                    ]
                                ]
                            ];
                            $variants[$vIndex] = $data;
                        }
                    }
                }
                
                app('api')->productVariants()->create($model->encodedId(), ['variants' => $variants]);
                foreach ($model->variants as $variant) {
                    $variant->image()->associate($model->primaryAsset());
                    $variant->save();
                }
            } else {
                $variant = [];
                $variant['sku'] = $product['sku'];
                $variant['price'] = $product['price'];
                $variant['inventory'] = $product['stock'];
                $variant['tax_id'] = $product['tax_id'];
                $variant['pricing'] = $product['prices'];
                $variant['tiers'] = $product['tiers'];
                $variant['options'] = [];
                $variant['weight'] = [
                    'value' => $product['weight'],
                    'unit' => 'lb'
                ];

                app('api')->productVariants()->create($model->encodedId(), ['variants' => [$variant]]);
                
                foreach ($model->variants as $variant) {
                    $variant->image()->associate($model->primaryAsset());
                    $variant->save();
                }
            }

            if (!empty($product['categories'])) {
                foreach ($product['categories'] as $pc) {
                    $category = app('api')->categories()->getById($pc['category_id']);
                    $model->categories()->attach($category);
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info('');
    }

    /**
     * Maps option data
     *
     * @param array $options
     * @return void
     */
    protected function mapOptionData(array $options)
    {
        $optionData = [];
        foreach ($options as $index => $option) {
            foreach ($option['description'] as $description) {
                $optionData[$index]['label'][$description['lang_code']] = $description['option_name'];
            }

            $optionData[$index]['options'] = [];

            $i = 1;

            foreach ($option['variants'] as $vIndex => $variant) {
                // $optionData[$index]['options'][$vIndex]['values']
                $values = [];
                foreach ($variant['description'] as $item) {
                    $values[$item['lang_code']] = $item['variant_name'];
                }
                $optionData[$index]['options'][$vIndex]['values'] = $values;
                $optionData[$index]['options'][$vIndex]['position'] = $i++;
            }
        }
        return $optionData;
    }

    /**
     * Import customer groups
     *
     * @return void
     */
    protected function importCustomerGroups()
    {
        $this->info('Importing Customer Groups');
        $groups = $this->importer->getCustomerGroups();
        $bar = $this->output->createProgressBar(count($groups));

        foreach ($groups as $group) {
            app('api')->customerGroups()->create($group);
            $bar->advance();
        }

        $bar->finish();
        $this->info('');
    }
}
