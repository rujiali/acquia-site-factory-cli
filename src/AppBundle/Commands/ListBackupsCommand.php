<?php
/**
 * @file
 *   ListBackups commands.
 */
namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\ConnectorSites;

/**
 * Class ListBackupsCommand
 */
class ListBackupsCommand extends Command
{
    protected $connectorSites;

    /**
     * ListBackupsCommand constructor.
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
            ->setName('app:listBackups')
            ->setDescription('Command to list all the backups');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $backups = $this->connectorSites->listBackups();
        if (is_array($backups)) {
            $headers = ['id', 'nid', 'status', 'uid', 'timestamp', 'file', 'label'];
            $backuplist = [];
            foreach ($backups as $backup) {
                $backuplist[] = [
                'id' => $backup->id,
                'nid' => $backup->nid,
                'status' => $backup->status,
                'uid' => $backup->uid,
                'timestamp' => $backup->timestamp,
                'file' => $backup->file,
                'label' => $backup->label,
                ];
            }
            $table = new Table($output);
            $table
                ->setHeaders($headers)
                ->setRows($backuplist);
            $table->render();
        } else {
            $output->write($backups);
        }
    }
}
