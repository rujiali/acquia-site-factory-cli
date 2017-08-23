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
class ConnectorSites
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
        if ($this->url && $this->username && $this->password) {
            try {
                $result = $this->client->get(
                    $this->url.'/api/v1/sites',
                    [
                    'auth' => [
                    $this->username,
                    $this->password,
                    ],
                    'query' => [
                        'limit' => $limit,
                        'page' => $page,
                        'canary' => $canary,
                    ],
                    ]
                );

                $bodyText = json_decode($result->getBody());

                return $bodyText->sites;
            } catch (ClientException $e) {
                if ($e->hasResponse()) {
                    return json_decode($e->getResponse()->getBody())->message;
                }

                return 'Cannot connect to site factory';
            }
        }

        return 'Cannot find details in sitefactory.yml file.';
    }
}
