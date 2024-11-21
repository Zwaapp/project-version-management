<?php

namespace App\Console\Commands;

use App\Domain\Project\Actions\FetchAndCreateProjectsAction;
use Illuminate\Console\Command;

class fetchProjectsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch_projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch projects from any given source.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(FetchAndCreateProjectsAction::class)();
    }
}
