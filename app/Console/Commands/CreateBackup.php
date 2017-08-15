<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Connector\Connector;

class CreateBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:createBackup {label=AutoBackup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create backup for specific site in site factory';

    /**
     * @var \App\Connector\Connector
     */
    protected $connector;

    /**
     * CreateBackup constructor.
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
        $task_id = $this->connector->createBackup($this->argument('label'));
        $this->info($task_id);
    }
}
