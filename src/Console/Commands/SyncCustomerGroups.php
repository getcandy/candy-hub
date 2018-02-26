<?php

namespace GetCandy\Console\Commands;

use Illuminate\Console\Command;

class SyncCustomerGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:trade';

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
        $users = \GetCandy\Api\Auth\Models\User::all();

        foreach ($users as $user) {
            $trade = $user->groups->contains(function ($group) {
                return $group->handle == 'trade';
            });

            // If they have a trade account, make sure they aren't retail.
            if ($trade) {
                $user->groups()->detach(3);
            }
        }
    }
}
