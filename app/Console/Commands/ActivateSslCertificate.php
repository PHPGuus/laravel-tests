<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Forge\Forge;

class ActivateSslCertificate extends Command
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
    protected $signature = 'forge-sdk:activate-certificate { server : The server on which the site ' .
		'resides } { site : The ID of the Site for which to obtain an SSH certificate }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate the first non-active certificate on the server for the site.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$this->forge = new Forge(env('LARAVEL_FORGE_TOKEN'));

		$certificate = $this->getCertificate();

		$this->forge->activateCertificate($certificate->serverId, $certificate->siteId, $certificate->id);

		$this->components->info('Successfully activated the first inactive certificate');
		
		return Command::SUCCESS;
    }

	protected function getCertificate()
	{
		$serverId = $this->argument('server');
		$siteId = $this->argument('site');

		$certificates = $this->forge->certificates($serverId, $siteId);
		foreach($certificates as $certificate) {
			if(!$certificate->active) {
				return $certificate;
			}
		}

		$this->components->error('Could not find an inactive certificate.');
		exit(Command::FAILURE);
	}
}
