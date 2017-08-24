<?php
/**
 * @file
 *   Clear cache commands.
 */
namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Connector\ConnectorSites;

/**
 * Class ClearCacheCommand
 */
class ClearCacheCommand extends Command
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
            ->setName('app:clearCache')
            ->setDescription('Clear site cache');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $task = $this->connectorSites->clearCache();
        if (is_object($task)) {
            $headers = ['id', 'time', 'drupal_cache_clear_task_id', 'varnish_cache_clear_task_id'];
            $taskList = [];
            $taskList[] = [
              'id' => $task->id,
              'time' => $task->time,
                // @codingStandardsIgnoreStart
              'drupal_cache_clear-task_id' => $task->task_ids->drupal_cache_clear,
              'varnish_cache_clear-task_id' => $task->task_ids->varnish_cache_clear,
                // @codingStandardsIgnoreEnd
            ];
            $table = new Table($output);
            $table
                ->setHeaders($headers)
                ->setRows($taskList);
            $table->render();
        } else {
            $output->write($task);
        }
    }
}
