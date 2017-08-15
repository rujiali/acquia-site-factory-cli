<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Connector\Connector;

class getBackupURL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getBackupURL';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to get the temporary backup url.';

    protected $connector;

    /**
     * Create a new command instance.
     *
     * @return void
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
        $url = $this->connector->getBackupURL();
        $this->info($url);
    }
}
