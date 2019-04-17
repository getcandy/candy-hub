<?php

namespace GetCandy\Hub\Jobs\Products;

use Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GetCandy\Hub\Mail\ExportCompleteMailer;
use GetCandy\Api\Core\Products\Models\Product;

class ExportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function handle()
    {
        // Do the export in batches of 1000
        $current = 1;
        $offset = 0;
        $batchSize = 1000;
        $locale = app()->getLocale();

        $products = Product::withoutGlobalScopes()->limit(1000)->offset($offset)->get();

        $headers = [
            'sku',
            'enabled',
            'price',
            'unit_qty',
            'min_qty',
            'min_batch',
            'stock',
            'backorder',
        ];

        $rows = [];

        $file = str_random();

        $filename = 'exports/' . $file . '.csv';

        // Make our temporary csv.
        $csv = \Storage::put($filename, '');

        while ($products->count()) {
            foreach ($products as $product) {
                $data = [];

                // Set up the attributes
                // Go through and add the base stuff
                foreach ($product->variants as $variant) {
                    $data = [
                        $variant->sku,
                        empty($variant->product->deleted_at) ? '1' : '0',
                        $variant->price,
                        $variant->unit_qty,
                        $variant->min_qty,
                        $variant->min_batch,
                        $variant->stock,
                        $variant->backorder,
                    ];
                }



                foreach ($product->attribute_data as $handle => $channels) {
                    if (array_search($handle, $headers) === false) {
                        array_push($headers, $handle);
                    }
                    foreach ($channels as $channel => $value) {
                        if ($channel != 'webstore') {
                            continue;
                        }
                        $value = $value[$locale];
                        $data[] = $value;
                    }
                }
                $rows[] = $data;
            }
            $offset += $batchSize;
            $products = Product::withoutGlobalScopes()->limit($batchSize)->offset($offset)->get();
        }

        array_unshift($rows, $headers);

        $fp = fopen(storage_path('app/' . $filename), 'w');

        foreach ($rows as $row) {
            fputcsv($fp, $row);
        }

        fclose($fp);

        Mail::to($this->email)
        ->send(new ExportCompleteMailer($file));
    }
}
