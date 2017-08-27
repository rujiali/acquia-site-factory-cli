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
    protected $config = NULL;

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

        if (file_exists($root.'sitefactory.yml')) {
            $this->config = $parser->parse(file_get_contents($root.'sitefactory.yml'));
            $this->username = $this->config['username'];
            $this->password = $this->config['apikey'];
            $this->url = $this->config['url'];
            $this->siteId = $this->config['site_id'];
        }
    }

    /**
     * Connect to site factory.
     *
     * @param string $url    site factory url.
     * @param array  $params Parameters.
     * @param string $method Request method.
     *
     * @return mixed|string
     */
    public function connecting($url, $params, $method)
    {
        if ($this->config == NULL) {
            return NULL;
        }
        try {
            $result = $this->client->request(
                $method,
                $url,
                [
                'auth' => [
                  $this->username,
                  $this->password,
                ],
                'query' => $params,
                ]
            );

            $bodyText = json_decode($result->getBody());

            return $bodyText;
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                return json_decode($e->getResponse()->getBody())->message;
            }

            return 'Cannot get backup URL.';
        }
    }

    /**
     * Get site ID.
     *
     * @return int|mixed
     */
    public function getSiteID()
    {
        try {
            return $this->siteId;
        } catch (\Exception $e) {
            return NULL;
        }
    }

    /**
     * Get Site factory URL.
     *
     * @return mixed|string
     */
    public function getURL()
    {
        try {
            return $this->url;
        } catch (\Exception $e) {
            return NULL;
        }
    }

    /**
     * Ping site factory.
     *
     * @return string
     */
    public function ping()
    {
        if ($this->getURL() == NULL) {
            return 'Cannot find site URL from configuration.';
        }
        $url = $this->getURL().'/api/v1/ping';
        $params = [];
        $response = $this->connecting($url, $params, 'GET');

        if (isset($response->message)) {
            return $response->message;
        }

        return $response;
    }
}
