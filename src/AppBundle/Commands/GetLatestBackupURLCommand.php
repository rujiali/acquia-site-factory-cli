<?php
/**
 * @file
 *   Get latest backup URL command.
 */
namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\ConnectorSites;

/**
 * Class GetLatestBackupURLCommand
 */
class GetLatestBackupURLCommand extends Command
{
    protected $connectorSites;

    /**
     * GetLatestBackupURLCommand constructor.
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
            ->setName('app:getLatestBackupURL')
            ->setDescription('Command to get the temporary backup url.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $this->connectorSites->getLatestBackupURL();
        $output->write($url);
    }
}
