<?php
/**
 * @file
 *   SendNotification command.
 */
namespace AppBundle\Commands;

use AppBundle\Connector\ConnectorThemes;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SendNotificationCommand
 */
class SendNotificationCommand extends Command
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
            ->setName('app:sendThemeNotification')
            ->setDescription('Command to send a theme event notification to site factory')
            ->addArgument('scope', InputArgument::REQUIRED, 'The scope')
            ->addArgument('event', InputArgument::REQUIRED, 'The type of theme event')
            ->addArgument('nid', InputArgument::OPTIONAL, 'The node ID of the related entity (site or group). Not relevant for the "global" scope.')
            ->addArgument('theme', InputArgument::OPTIONAL, 'The system name of the theme. Only relevant for "theme" scope notifications.')
            ->addArgument('timestamp', InputArgument::OPTIONAL, 'A Unix timestamp of when the event occurred.')
            ->addArgument('uid', InputArgument::OPTIONAL, 'The user id owning the notification and who should get notified if an error occurs during processing.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scope = $input->getArgument('scope');
        $event = $input->getArgument('event');
        $nid = $input->getArgument('nid');
        $theme = $input->getArgument('theme');
        $timestimp = $input->getArgument('timestamp');
        $uid = $input->getArgument('uid');
        $message = $this->connectorThemes->sendNotification($scope, $event, $nid, $theme, $timestimp, $uid);
        $output->writeln($message);
    }
}
