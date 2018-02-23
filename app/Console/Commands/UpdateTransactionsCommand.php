<?php

namespace GetCandy\Console\Commands;

use Illuminate\Console\Command;

class UpdateTransactionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync transactions with the remote service';

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
        // Get all orders that are payment pending.
        $orders = app('api')->orders()->getPending();

        foreach ($orders as $order) {
            foreach ($order->transactions as $transaction) {
                $provider = app('api')->payments()->setProvider($transaction->driver)->getProvider();
                $provider->updateTransaction($transaction);
            }
        }
    }
}
