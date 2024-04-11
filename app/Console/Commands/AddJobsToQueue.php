<?php

namespace App\Console\Commands;

use App\Jobs\QueueJob;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class AddJobsToQueue extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-jobs-to-queue { --amount= : The number of jobs to add to the queue } { name : The name of the job }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add jobs to the queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $max = 1;
        if($this->option('amount') > $max) {
            $max = $this->option('amount');
        }
        $name = $this->argument('name');

        if($max === 1) {
            QueueJob::dispatch($name);
        } else {
            $i = 1;
            while($i <= $max) {
                QueueJob::dispatch($name.str_pad((string) $i, strlen((string) $max), '0', STR_PAD_LEFT));
                $i++;
            }
        }
    }
}
