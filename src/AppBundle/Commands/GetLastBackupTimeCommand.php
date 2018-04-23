<?php
/**
 * @file
 *   Get last backup timestamp command.
 */
namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\ConnectorSites;

/**
 * Class GetLatestBackupTimeCommand
 */
class GetLastBackupTimeCommand extends Command
{
    protected $connectorSites;

    /**
     * GetLastBackupTimeCommand constructor.
     * @param \AppBundle\Connector\ConnectorSites $connectorSites
     */
    public function __construct(ConnectorSites $connectorSites)
    {
        $this->connectorSites = $connectorSites;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:getLastBackupTime')
            ->setDescription('Command to get the last backup timestamp.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timestamp = $this->connectorSites->getLastBackupTime();
        $output->write($timestamp);
    }
}
