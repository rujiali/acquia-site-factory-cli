<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Connector\Connector;

class GetLatestBackupURL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getLatestBackupURL';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to get the temporary backup url.';

    /**
     * @var \App\Connector\Connector
     */
    protected $connector;

    /**
     * GetLatestBackupURL constructor.
     *
     * @param \App\Connector\Connector $connector
     */
    public function __construct(Connector $connector)
    {
        parent::__construct();
        $this->connector = $connector;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = $this->connector->getLatestBackupURL();
        $this->info($url);
    }
}
