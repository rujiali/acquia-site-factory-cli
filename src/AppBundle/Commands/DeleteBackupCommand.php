<?php
/**
 * @file
 *   DeleteBackup command.
 */
namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\ConnectorSites;

/**
 * Class DeleteBackupCommand
 */
class DeleteBackupCommand extends Command
{
    protected $connectorSites;

    /**
     * DeleteBackupCommand constructor.
     *
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
            ->setName('app:deleteBackup')
            ->setDescription('Command to create backup for specific site in site factory')
            ->addArgument('backupId', InputArgument::REQUIRED, 'Backup ID')
            ->addArgument('callback_url', InputArgument::OPTIONAL, 'Callback URL')
            ->addArgument('callback_method', InputArgument::OPTIONAL, 'Callback method')
            ->addArgument('caller_data', InputArgument::OPTIONAL, 'Caller data')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $backupId = $input->getArgument('backupId');
        $callbackUrl = $input->getArgument('callback_url');
        $callbackMethod = $input->getArgument('callback_method');
        $callerData = $input->getArgument('caller_data');
        $taskId = $this->connectorSites->deleteBackup($backupId, $callbackUrl, $callbackMethod, $callerData);
        $output->writeln($taskId);
    }
}
