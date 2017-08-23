<?php
/**
 * @file
 *   Connector file.
 */
namespace AppBundle\Connector;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Yaml\Parser;

/**
 * Class Connector
 */
class Connector
{

    /**
     * @var string
     *   username in site factory.
     */
    protected $username;

    /**
     * @var string
     *   apikey in site factory.
     */
    protected $password;

    /**
     * @var string
     *   Site URL in site factory.
     */
    protected $url;

    /**
     * @var int
     *   Site ID.
     */
    protected $siteId;

    /**
     * @var array
     *   Configurations array.
     */
    protected $config;

    /**
     * @var string.
     *   Path to root.
     */
    protected $root;

    /**
     * Connector constructor.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        if (file_exists(__DIR__.'/../../../../../autoload.php')) {
            $root = __DIR__.'/../../../../../../';
        } else {
            $root = __DIR__.'/../../../';
        }
        $this->client = $client;
        $parser = new Parser();
        $this->root = $root;

        $this->config = $parser->parse(file_get_contents($root.'sitefactory.yml'));
        $this->username = $this->config['username'];
        $this->password = $this->config['apikey'];
        $this->url = $this->config['url'];
        $this->siteId = $this->config['site_id'];
    }

    /**
     * Ping site factory.
     *
     * @return string
     */
    public function ping()
    {
        if ($this->url && $this->username && $this->password) {
            try {
                $result = $this->client->get(
                    $this->url.'/api/v1/ping',
                    [
                    'auth' => [
                    $this->username,
                    $this->password,
                    ],
                    ]
                );

                $bodyText = json_decode($result->getBody());

                return $bodyText->message;
            } catch (ClientException $e) {
                if ($e->hasResponse()) {
                    return json_decode($e->getResponse()->getBody())->message;
                }

                return 'Cannot connect to site factory';
            }
        }

        return 'Cannot find details in sitefactory.yml file.';
    }

    /**
     * List all backups in for specific site in site factory.
     *
     * @return mixed
     */
    public function listBackups()
    {

        try {
            $result = $this->client->get(
                $this->url.'/api/v1/sites/'.$this->siteId.'/backups',
                [
                'auth' => [
                $this->username,
                $this->password,
                ],
                ]
            );
            $bodyText = json_decode($result->getBody());

            return $bodyText->backups;
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                return json_decode($e->getResponse()->getBody())->message;
            }

            return 'Cannot find backup for this site.';
        }
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
        try {
            $result = $this->client->post(
                $this->url.'/api/v1/sites/'.$this->siteId.'/backup',
                [
                'auth' => [
                $this->username,
                $this->password,
                ],
                'json' => ['label' => $label],
                ]
            );
            $bodyText = json_decode($result->getBody());

            // @codingStandardsIgnoreStart
            return $bodyText->task_id;
            // @codingStandardsIgnoreEnd
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                return json_decode($e->getResponse()->getBody())->message;
            }

            return 'Cannot create backup.';
        }
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
        try {
            $result = $this->client->get(
                $this->url.'/api/v1/sites/'.$this->siteId.'/backups/'.$backupId.'/url',
                [
                'auth' => [
                $this->username,
                $this->password,
                ],
                ]
            );

            $bodyText = json_decode($result->getBody());

            return $bodyText->url;
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                return json_decode($e->getResponse()->getBody())->message;
            }

            return 'Cannot get backup URL.';
        }
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
}
