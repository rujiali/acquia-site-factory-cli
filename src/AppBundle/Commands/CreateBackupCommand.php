<?php

namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\Connector;

class CreateBackupCommand extends Command {
  protected $connector;

  public function __construct(Connector $connector) {
    $this->connector = $connector;
    parent::__construct();
  }

  protected function configure() {
    $this
      ->setName('app:createBackup')
      ->setDescription('Command to create backup for specific site in site factory')
      ->addArgument('label', InputArgument::OPTIONAL, 'Backup Label');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $label = $input->getArgument('label');
    if (!$label) {
      $label = 'Auto backup.';
    }
    $task_id = $this->connector->createBackup($label);
    $output->writeln($task_id);
  }
}
