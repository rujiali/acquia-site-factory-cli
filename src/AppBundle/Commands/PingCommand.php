<?php

namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\Connector;

class PingCommand extends Command
{
    protected $connector;

    public function __construct(Connector $connector) {
        $this->connector = $connector;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:ping')
            ->setDescription('Ping site factory service');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pong = $this->connector->ping();
        $output->writeln($pong);
    }

}
