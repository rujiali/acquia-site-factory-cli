<?php

namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\Connector;

class GetLatestBackupURLCommand extends Command {
  protected $connector;

  public function __construct(Connector $connector) {
    $this->connector = $connector;
    parent::__construct();
  }

  protected function configure() {
    $this
      ->setName('app:getLatestBackupURL')
      ->setDescription('Command to get the temporary backup url.');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $url = $this->connector->getLatestBackupURL();
    $output->write($url);
  }

}
