<?php
/**
 * @file
 *   Get site details command.
 */
namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\ConnectorSites;

/**
 * Class GetSiteDetailsCommand
 */
class GetSiteDetailsCommand extends Command
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
            ->setName('app:getSiteDetails')
            ->addArgument('siteID', InputArgument::REQUIRED)
            ->setDescription('Command to get site details.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $details = $this->connectorSites->getSiteDetails($input->getArgument('siteID'));
        if (isset($details->id)) {
            $table1 = new Table($output);
            $headers = ['id', 'created', 'owner', 'site', 'domains', 'groups', 'part of collection', 'is_primary', 'collection_id', 'collection_domains'];
            $row[] = [
              'id' => $details->id,
              'created' => $details->created,
              'owner' => $details->owner,
              'site' => $details->site,
              'domains' => implode(',', $details->domains),
              'groups' => implode(',', $details->groups),
                // @codingStandardsIgnoreStart
              'part of collection' => $details->part_of_collection,
              'is_primary' => $details->is_primary,
              'collection_id' => $details->collection_id,
              'collection_domains' => implode(',', $details->collection_domains),
                // @codingStandardsIgnoreEnd
            ];
            $table1
              ->setHeaders($headers)
              ->setRows($row);
            $table1->render();
        } else {
            $output->write($details);
        }
    }
}
