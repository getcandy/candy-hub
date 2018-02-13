<?php

namespace GetCandy\Console\Commands;

use Illuminate\Console\Command;

class FixInvoiceReference extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:invoices';

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
        $orders = \GetCandy\Api\Orders\Models\Order::withoutGlobalScopes()->whereIn('status', ['dispatched', 'payment-received'])->get();

        $ref = 1;

        foreach ($orders as $order) {
            $order->reference = $ref;
            $order->save();
            $ref++;
        }

        dd($order->count() + 1);
    }
}
