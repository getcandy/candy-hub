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
        $driver = $this->argument('driver');
        $this->importer = app($driver . '.importer');

        $this->importChannels();
        $this->importProductFamilies();
        $this->importCustomerGroups();
        $this->importProducts();
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
        $this->info('Importing Channels');
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
