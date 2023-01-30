<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Forge\Forge;

class DestroySslCertificate extends Command
{
	/**
	 * The Forge SDK Client.
	 *
	 * @var Forge
	 */
	protected Forge $forge;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forge-sdk:delete-certificate { server : The server on which the site ' .
		'resides } { site : The ID of the Site for which to obtain an SSH certificate }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a certificate that is active on the server.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$this->forge = new Forge(env('LARAVEL_FORGE_TOKEN'));

		$certificate = $this->getCertificate();

		$this->forge->deleteCertificate($certificate->serverId, $certificate->siteId, $certificate->id);

		$this->components->info('Successfully deleted the active certificate');
		
		return Command::SUCCESS;
    }

	protected function getCertificate()
	{
		$serverId = $this->argument('server');
		$siteId = $this->argument('site');

		$certificates = $this->forge->certificates($serverId, $siteId);
		foreach($certificates as $certificate) {
			if($certificate->active) {
				return $certificate;
			}
		}

		$this->components->error('Could not find an active certificate.');
		exit(Command::FAILURE);
	}
}
