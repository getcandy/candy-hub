<?php

namespace GetCandy\Console\Commands;

use Illuminate\Console\Command;

class SyncPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:sync:pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs Pending Orders';

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
        $provider = app('api')->payments()->getProvider();

        $transactions = app('api')->transactions()->all();


        dd($transactions);
    }
}
