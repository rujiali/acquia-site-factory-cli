<?php
/**
 * @file
 *   Connector file.
 */
namespace AppBundle\Connector;

use AppBundle\Connector\Connector;

/**
 * Class Connector
 */
class ConnectorSites
{
    protected $connector;

    /**
     * Connector constructor.
     *
     * @param \AppBundle\Connector\Connector $connector
     */
    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Clear site cache.
     *
     * @return mixed|string
     */
    public function clearCache()
    {
        $url = $this->connector->getURL().'/api/v1/sites/'.$this->connector->getSiteID().'/cache-clear';
        $params = [];
        $response = $this->connector->connecting($url, $params, 'POST');

        return $response;
    }

    /**
     * Create backup for specific site in site factory.
     *
     * @param string $label Backup label.
     *
     * @return integer
     *   Task ID.
     */
    public function createBackup($label)
    {
        $url = $this->connector->getURL().'/api/v1/sites/'.$this->connector->getSiteID().'/backup';
        $params = [
            'label' => $label,
        ];

        $response = $this->connector->connecting($url, $params, 'POST');

        // @codingStandardsIgnoreStart
        if (isset($response->task_id)) {
            return $response->task_id;
            // @codingStandardsIgnoreEnd
        }

        return $response;
    }

    /**
     * Get backup URL.
     *
     * @param string $backupId Backup ID.
     *
     * @return string
     */
    public function getBackupURL($backupId)
    {
        $url = $this->connector->getURL().'/api/v1/sites/'.$this->connector->getSiteID().'/backups/'.$backupId.'/url';
        $params = [];
        $response = $this->connector->connecting($url, $params, 'GET');
        if (isset($response->url)) {
            return $response->url;
        }

        return $response;
    }

    /**
     * Get backup URL for latest backup.
     *
     * @return string
     */
    public function getLatestBackupURL()
    {
        // Find out the latest backup ID.
        $backups = $this->listBackups();
        if (is_array($backups)) {
            if (!empty($backups)) {
                $backupId = $backups[0]->id;

                return $this->getBackupURL($backupId);
            }

            return 'There is no backup available.';
        }

        return $backups;
    }

    /**
     * Get site details
     *
     * @param int $siteId Site ID.
     *
     * @return mixed|string
     */
    public function getSiteDetails($siteId)
    {
        $url = $this->connector->getURL().'/api/v1/sites/'.$siteId;
        $params = [];
        $response = $this->connector->connecting($url, $params, 'GET');

        return $response;
    }

    /**
     * List all backups in for specific site in site factory.
     *
     * @return mixed
     */
    public function listBackups()
    {
        $url = $this->connector->getURL().'/api/v1/sites/'.$this->connector->getSiteID().'/backups';
        $params = [];
        $response = $this->connector->connecting($url, $params, 'GET');

        if (isset($response->backups)) {
            return $response->backups;
        }

        return $response;
    }

    /**
     * List sites.
     *
     * @param int     $limit  Limit number.
     * @param int     $page   Page number.
     * @param boolean $canary No value necessary.
     *
     * @return string
     */
    public function listSites($limit, $page, $canary = false)
    {
        $params = [
          'limit' => $limit,
          'page' => $page,
          'canary' => $canary,
        ];
        $url = $this->connector->getURL().'/api/v1/sites';

        $response = $this->connector->connecting($url, $params, 'GET');

        if (isset($response->sites)) {
            return $response->sites;
        }

        return $response;
    }
}
