<?php
namespace AppBundle\Connector;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use Symfony\Component\Yaml\Parser;

/**
 * Class Connector
 *
 * @package App\Connector
 */
class Connector {

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
   * @var integer
   *   Site ID.
   */
  protected $siteId;

  /**
   * @var \GuzzleHttp\Client
   *   Guzzle client.
   */
  protected $client;

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
   */
  public function __construct($root) {
    $this->client = new Client();
    $parser = new Parser();
    $this->root = $root;

    $this->config = $parser->parse(file_get_contents($root . 'sitefactory.yml'));
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
  public function ping() {
    if ($this->url && $this->username && $this->password) {
      try {
        $result = $this->client->get($this->url . '/api/v1/ping', [
          'auth' => [
            $this->username,
            $this->password
          ]
        ]);

        $bodyText = json_decode($result->getBody());

        return $bodyText->message;
      } catch (ClientException $e) {
        if ($e->hasResponse()) {
          return $e->getResponse()->getBody()->message;
        }
        else {
          return 'Cannot connect to site factory';
        }
      }
    }
    else {
      return 'Cannot find details in sitefactory.yml file.';
    }
  }

  /**
   * List all backups in for specific site in site factory.
   *
   * @return mixed
   */
  public function listBackups() {

    if ($this->ping() == 'pong') {
      try {
        $result = $this->client->get($this->url . '/api/v1/sites/' . $this->siteId . '/backups',
          [
            'auth' => [
              $this->username,
              $this->password
            ],
          ]);
        $bodyText = json_decode($result->getBody());

        return $bodyText->backups;
      } catch (ClientException $e) {
        if ($e->hasResponse()) {
          return json_decode($e->getResponse()->getBody())->message;
        }
        else {
          return 'Cannot find backup for this site.';
        }
      }
    }
    else {
      return $this->ping();
    }

  }

  /**
   * Create backup for specific site in site factory.
   *
   * @param $label
   *   Backup label.
   *
   * @return integer
   *   Task ID.
   */
  public function createBackup($label) {
    if ($this->ping() == 'pong') {
      try {
        $result = $this->client->post($this->url . '/api/v1/sites/' . $this->siteId . '/backup',
          [
            'auth' => [
              $this->username,
              $this->password
            ],
            'json' => ['label' => $label],
          ]);
        $bodyText = json_decode($result->getBody());

        return $bodyText->task_id;
      } catch (ClientException $e) {
        if ($e->hasResponse()) {
          return json_decode($e->getResponse()->getBody())->message;
        }
        else {
          return 'Cannot create backup.';
        }
      }
    }
    else {
      return $this->ping();
    }
  }

  /**
   * Get backup URL for latest backup.
   *
   * @return string
   */
  public function getLatestBackupURL() {
    if ($this->ping() == 'pong') {
      // Find out the latest backup ID.
      $backups = $this->listBackups();
      if (is_array($backups)) {
        if (!empty($backups)) {
          $backup_id = $backups[0]->id;
          try {
            $result = $this->client->get($this->url . '/api/v1/sites/' . $this->siteId . '/backups/' . $backup_id . '/url',
              [
                'auth' => [
                  $this->username,
                  $this->password
                ],
              ]);

            $bodyText = json_decode($result->getBody());

            return $bodyText->url;
          } catch (ClientException $e) {
            if ($e->hasResponse()) {
              return json_decode($e->getResponse()->getBody())->message;
            }
            else {
              return 'Cannot get backup URL.';
            }
          }
        }
        else {
          return 'There is no backup available.';
        }
      }
      else {
        return $backups;
      }
    }
    else {
      return $this->ping();
    }
  }

}
