<?php
/**
 * @file
 *   Get latest backup URL command.
 */
namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\Connector;

/**
 * Class GetLatestBackupURLCommand
 */
class GetLatestBackupURLCommand extends Command
{
    protected $connector;

    /**
     * GetLatestBackupURLCommand constructor.
     * @param \AppBundle\Connector\Connector $connector
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
            ->setName('app:getLatestBackupURL')
            ->setDescription('Command to get the temporary backup url.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $this->connector->getLatestBackupURL();
        $output->write($url);
    }
}
