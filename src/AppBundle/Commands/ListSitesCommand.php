<?php
/**
 * @file
 *   ListSites commands.
 */
namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\ConnectorSites;

/**
 * Class ListBackupsCommand
 */
class ListSitesCommand extends Command
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
            ->setName('app:listSites')
            ->addArgument('limit', InputArgument::OPTIONAL)
            ->addArgument('page', InputArgument::OPTIONAL)
            ->addArgument('canary', InputArgument::OPTIONAL)
            ->setDescription('Command to list all the sites');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sites = $this->connectorSites->listSites($input->getArgument('limit'), $input->getArgument('page'), $input->getArgument('canary'));
        if (is_array($sites)) {
            $headers = ['id', 'site', 'domain', 'site_collection', 'is_primary'];
            $sitelist = [];
            foreach ($sites as $site) {
                $sitelist[] = [
                'id' => $site->id,
                'site' => $site->site,
                'domain' => $site->domain,
                // @codingStandardsIgnoreStart
                'site_collection' => $site->site_collection,
                'is_primary' => $site->is_primary,
                // @codingStandardsIgnoreEnd
                ];
            }
            $table = new Table($output);
            $table
                ->setHeaders($headers)
                ->setRows($sitelist);
            $table->render();
        } else {
            $output->write($sites);
        }
    }
}
