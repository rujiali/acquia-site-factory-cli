<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Connector\Connector;

class Ping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping site factory service';

    /**
     * @var \App\Connector\Connector
     */
    protected $connector;

    /**
     * Ping constructor.
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
        $pong = $this->connector->ping();
        $this->info($pong);
    }
}
