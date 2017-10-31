<?php

namespace GetCandy\Console\Commands;

use Illuminate\Console\Command;
use DB;

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

        $this->importChannels();
        $this->importProductFamilies();
        $this->importCustomerGroups();
        $this->importCategories();
        $this->importProducts();
    }

    protected function importCategories()
    {
        $this->info('Importing Categories');
        $categories = $this->importer->getCategories();
        $bar = $this->output->createProgressBar(count($categories));

        foreach ($categories as $category) {
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

    public function importProductFamilies()
    {
        $this->info('Importing Product Families');
        $families = $this->importer->getProductFamilies();
        $bar = $this->output->createProgressBar(count($families));

        foreach ($families as $family) {
            app('api')->productFamilies()->create($family);
            $bar->advance();
        }

        $bar->finish();
        $this->info('');
    }

    public function importProducts()
    {
        $this->info('Importing Products');
        $products = $this->importer->getProducts();
        $bar = $this->output->createProgressBar(count($products));

        foreach ($products as $product) {
            $model = app('api')->products()->create($product);

            if (!empty($product['categories'])) {
                foreach ($product['categories'] as $pc) {
                    $category = app('api')->categories()->getById($pc['category_id']);
                    $model->categories()->attach($category);
                }
            }

            $attributes = \GetCandy\Api\Attributes\Models\Attribute::get();
            foreach ($attributes as $att) {
                $model->attributes()->attach($att);
            }

            foreach ($product['images'] as $image) {
                app('api')->assets()->upload($image, $model);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->info('');
    }

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
