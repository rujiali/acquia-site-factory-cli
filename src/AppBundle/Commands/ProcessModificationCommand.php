<?php
/**
 * @file
 *   ProcessModification command.
 */
namespace AppBundle\Commands;

use AppBundle\Connector\ConnectorThemes;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProcessModificationCommand
 */
class ProcessModificationCommand extends Command
{
    protected $connectorThemes;

    /**
     * SendNotification Command constructor.
     *
     * @param \AppBundle\Connector\ConnectorThemes $connectorThemes
     */
    public function __construct(ConnectorThemes $connectorThemes)
    {
        $this->connectorThemes = $connectorThemes;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:processModification')
            ->setDescription('Command to process a theme modification to site factory')
            ->addArgument('sitegroup_id', InputArgument::OPTIONAL, 'The site group ID.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sitegroupId = $input->getArgument('sitegroup_id');
        $message = $this->connectorThemes->processModification($sitegroupId);
        $output->writeln($message);
    }
}
