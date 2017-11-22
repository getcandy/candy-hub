<?php

namespace GetCandy\Console\Commands;

use Illuminate\Console\Command;
use GetCandy\Search\SearchContract;

class IndexRefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $products = \GetCandy\Api\Products\Models\Product::with('variants')->take(10)->get();
        // $categories = \GetCandy\Api\Categories\Models\Category::whereNull('parent_id')->get();
        $bar = $this->output->createProgressBar($products->count() + $categories->count());
        $langs = app('api')->languages()->getDataList();
        foreach ($langs as $lang) {
            app(SearchContract::class)->reset(config('search.index_prefix') . '_' . $lang->lang);
        }
        foreach ($products as $product) {
            app(SearchContract::class)->indexObject($product);
            $bar->advance();
        }
        // foreach ($categories as $category) {
        //     app(SearchContract::class)->indexObject($category);
        //     $bar->advance();
        // }
        $bar->finish();
    }
}
