<?php

namespace App\Console\Commands;

use App\Jobs\HorizonJob;
use Illuminate\Console\Command;

class DispatchJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch a job';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        HorizonJob::dispatch();

        return Command::SUCCESS;
    }
}
