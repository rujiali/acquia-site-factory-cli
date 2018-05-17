<?php
/**
 * @file
 *   Wait backup commands.
 */
namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\ConnectorSites;

/**
 * Class WaitBackupCommand
 */
class WaitBackupCommand extends Command
{
    protected $connectorSites;

    /**
     * ListBackupsCommand constructor.
     *
     * @param \AppBundle\Connector\ConnectorSites $connector
     */
    public function __construct(ConnectorSites $connector)
    {
        $this->connectorSites = $connector;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:waitBackup')
            ->setDescription('Wait until the required backup is generated.')
            ->addArgument('label', InputArgument::REQUIRED, 'Backup label');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start to wait for the backup');

        if ($this->connectorSites->waitBackup($input->getArgument('label'))) {
            $output->writeln('Backup finished!');
        }
    }
}
