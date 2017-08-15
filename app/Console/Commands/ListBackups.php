<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Connector\Connector;

class ListBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:listBackups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to list all the backups';

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
        $backups = $this->connector->listBackups();
        if (is_array($backups)) {
            $headers = ['id', 'nid', 'status', 'uid', 'timestamp', 'file', 'label'];
            $backuplist = [];
            foreach ($backups as $backup) {
                $backuplist[] = [
                  'id' => $backup->id,
                  'nid' => $backup->nid,
                  'status' => $backup->status,
                  'uid' => $backup->uid,
                  'timestamp' => $backup->timestamp,
                  'file' => $backup->file,
                  'label' => $backup->label,
                ]
                ;
            }
            $this->table($headers, $backuplist);
        }
        else {
            $this->info($backups);
        }
    }
}
