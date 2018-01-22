<?php

namespace GetCandy\Console\Commands;

use Illuminate\Console\Command;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Attributes\Events\AttributableSavedEvent;

class SyncDescriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'desc:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync up product descriptions';

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
        $products = Product::withoutGlobalScopes()->get();

        $threshold = 250;

        $bar = $this->output->createProgressBar($products->count());

        foreach ($products as $product) {
            
            $data = $product->attribute_data;

            foreach ($data as $handle => $item) {
                if ($handle == 'description') {
                    foreach ($item as $channel => $row) {
                        foreach ($row as $locale => $value) {
                            $textLength = strlen(
                                trim(preg_replace('/\s+/', ' ', strip_tags($value)))
                            );
                            if ($textLength <= $threshold) {
                                $data['short_description'][$channel][$locale] = $value;
                                $data[$handle][$channel][$locale] = '';
                            } else {
                                $data['short_description'][$channel][$locale] = '';
                            }
                        }
                    }
                }
            }

            $product->attribute_data = $data;
            $product->save();

            event(new AttributableSavedEvent($product));
            $bar->advance();
        }
        $bar->finish();
    }
}
