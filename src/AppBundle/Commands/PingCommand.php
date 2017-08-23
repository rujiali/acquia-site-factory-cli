<?php
/**
 * @file
 *   Ping command class.
 */
namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\Connector;

/**
 * Class PingCommand
 */
class PingCommand extends Command
{
    protected $connector;

    /**
     * PingCommand constructor.
     *
     * @param \AppBundle\Connector\Connector $connector
     *   Connector service.
     */
    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:ping')
            ->setDescription('Ping site factory service');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pong = $this->connector->ping();
        $output->writeln($pong);
    }
}
