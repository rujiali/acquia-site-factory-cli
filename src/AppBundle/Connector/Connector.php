<?php
namespace AppBundle\Connector;

use GuzzleHttp\Exception\GuzzleException;
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
   * Connector constructor.
   */
  public function __construct() {
    $this->client = new Client();
    $parser = new Parser();

    $this->config = $parser->parse(file_get_contents(__DIR__ . '/../../../sitefactory.yml'));
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
    $result = $this->client->get($this->url . '/api/v1/ping', [
      'auth' => [
        $this->username,
        $this->password
      ]
    ]);

    $bodyText = json_decode($result->getBody());

    return $bodyText->message;
  }

  /**
   * List all backups in for specific site in site factory.
   *
   * @return mixed
   */
  public function listBackups() {

    if ($this->ping() == 'pong') {
      $result = $this->client->get($this->url . '/api/v1/sites/' . $this->siteId . '/backups' , [
        'auth' => [
          $this->username,
          $this->password
        ],
      ]);

      $status = $result->getStatusCode();
      
      if ($status != 404) {
        $bodyText = json_decode($result->getBody());

        return $bodyText->backups;
      }
      else {
        return 'No backup found.';
      }
    }
    else {
      return 'Site factory is not connected.';
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
      $result = $this->client->post($this->url . '/api/v1/sites/' . $this->siteId . '/backup' , [
        'auth' => [
          $this->username,
          $this->password
        ],
        'json' => ['label' => $label],
      ]);

      $status = $result->getStatusCode();

      if ($status != 404) {
        $bodyText = json_decode($result->getBody());

        return $bodyText->task_id;
      }
      else {
        return 'Backup failed';
      }
    }
    else {
      return 'Site factory is not connected.';
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
      if (is_array($backups) && !empty($backups)) {
        $backup_id = $backups[0]->id;
        $result = $this->client->get($this->url . '/api/v1/sites/' . $this->siteId . '/backups/' . $backup_id . '/url', [
          'auth' => [
            $this->username,
            $this->password
          ],
        ]);

        $status = $result->getStatusCode();

        if ($status != 404) {
          $bodyText = json_decode($result->getBody());

          return $bodyText->url;
        }
        else {
          return 'Cannot find backup url';
        }
      }
      else {
        return 'Can not find backup.';
      }

    }
    else {
      return 'Site factory is not connected.';
    }
    
  }

}
