<?php

namespace GetCandy\Plugins\LegacyPassword\Console\Commands;

use Illuminate\Console\Command;
use GetCandy\Search\SearchContract;
use GetCandy\Plugins\LegacyPassword\Models\LegacyPassword;

class ImportLegacyPasswordsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'legacypasswords:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import legacy passwords';

    protected $factory;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(\GetCandy\Importers\Aqua\Factory $factory)
    {
        parent::__construct();
        $this->factory = $factory;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = $this->factory->getUsers();
        $bar = $this->output->createProgressBar($users->count());
        foreach ($users as $user) {
            $legacy = new LegacyPassword;

            $legacy->user_id = $user->user_id;

            $legacy->password = $user->password;
            $legacy->salt = $user->salt;

            // Find user and set their password to null
            $user = \GetCandy\Api\Auth\Models\User::find($user->user_id);
            $user->password = null;
            $user->save();

            $legacy->save();
            $bar->advance();
        }
        $bar->finish();
    }
}
