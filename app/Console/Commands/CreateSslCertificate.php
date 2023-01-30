<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Forge\Forge;

class CreateSslCertificate extends Command
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
    protected $signature = 'forge-sdk:obtain-lets-encrypt-certificate { server : The server on which the site ' .
		'resides } { site : The ID of the Site for which to obtain an SSH certificate }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtain a Let\'s Encrypt certificate for the given server & site.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$this->forge = new Forge(env('LARAVEL_FORGE_TOKEN'));

		$site = $this->getSite();

		try {
			$certificate = $this->forge->obtainLetsEncryptCertificate($site->serverId, $site->id, [
				'domains' => [$site->name],
			], true);
		} catch(\Throwable $t) {
			$certificate = NULL;
		}

		if(!$certificate) {
			$this->components->error('Cannot obtain Let\'s Encrypt certificate');
			exit(Command::FAILURE);
		}

		$this->components->info('Certificate obtained and activated');
		
        return Command::SUCCESS;
    }

	protected function getSite()
	{
		$serverId = $this->argument('server');
		$siteId = $this->argument('site');
		try {
			$site = $this->forge->site($serverId, $siteId);
		} catch(\Throwable $t) {
			$site = NULL;
		}

		if(!$site) {
			$this->components->error('Cannot fetch the site for server ID "' . $serverId . '" and site ID "' . $siteId . '".');
			exit(Command::FAILURE);
		}

		return $site;
	}
}
