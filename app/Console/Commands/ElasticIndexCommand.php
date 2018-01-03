<?php

namespace GetCandy\Console\Commands;

use Illuminate\Console\Command;
use GetCandy\Search\SearchContract;

class ElasticIndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:index {--model=}';

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
        $model = $this->option('model');
        $model = new $model;

        $search = app(SearchContract::class);

        if (!$search->hasIndexer($model)) {
            $this->error("No Indexer found for {$model}");
        }

        foreach ($model->get() as $model) {
            dd($model);
            app(SearchContract::class)->indexObject($model);
            echo '.';
        }
        $this->info('Done!');
    }
}
