<?php
/**
 * @file
 *   CreateBackup command.
 */
namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\ConnectorSites;

/**
 * Class CreateBackupCommand
 */
class CreateBackupCommand extends Command
{
    protected $connectorSites;

    /**
     * CreateBackupCommand constructor.
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
            ->setName('app:createBackup')
            ->setDescription('Command to create backup for specific site in site factory')
            ->addArgument('label', InputArgument::OPTIONAL, 'Backup Label')
            ->addArgument('components', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'The components you want to put in backup (separate multiple names with a space)');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $label = $input->getArgument('label');
        if (!$label) {
            $label = 'Auto backup.';
        }

        $components = $input->getArgument('components');
        if (!$components) {
            $components = [];
        }

        $taskId = $this->connectorSites->createBackup($label, $components);
        $output->writeln($taskId);
    }
}
