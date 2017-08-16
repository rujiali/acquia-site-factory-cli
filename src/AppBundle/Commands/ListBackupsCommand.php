<?php

namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\Connector;

class ListBackupsCommand extends Command {
  protected $connector;

  public function __construct(Connector $connector) {
    $this->connector = $connector;
    parent::__construct();
  }

  protected function configure() {
    $this
      ->setName('app:listBackups')
      ->setDescription('Command to list all the backups');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
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
        ];
      }
      $table = new Table($output);
      $table
        ->setHeaders($headers)
        ->setRows($backuplist);
      $table->render();
    }
    else {
      $output->write($backups);
    }
  }

}
