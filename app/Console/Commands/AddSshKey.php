<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Forge\Forge;

class AddSshKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forge-sdk:add-ssh-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add an SSH key to Forge via the API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$this->info('Creating Forge object');

		$forge = new Forge(env('LARAVEL_FORGE_TOKEN'));

		$this->info('Adding key to server ID 266575 (clean-grove)...');

		$result = $forge->createSSHKey(266575, [
			'key' => 'ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIBkTBP6wR2MvDkoogHnIIB7eH61sBZIi0/RTVTi4LIV4 leeuwg@DESKTOP-PF7H05G',
			'name' => 'From API',
			'username' => 'forge',
		], true);

		$this->info('Key created with ID #' . $result->id);

        return Command::SUCCESS;
    }
}
