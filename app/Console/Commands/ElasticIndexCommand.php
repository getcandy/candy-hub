<?php

namespace GetCandy\Console\Commands;

use Illuminate\Console\Command;

class ElasticIndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:index {--class=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes a model for Elasticsearch';

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
        $model = $this->option('class');
        $model = new $model;

        $this->info('Deleting index...');
        $model->deleteIndex();

        $this->info('Reindexing...');

        foreach ($model->get() as $model) {
            $model->addToIndex();
        }

        $this->info('Index!');
    }
}
