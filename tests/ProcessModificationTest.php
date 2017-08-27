<?php
namespace tests;

use AppBundle\Connector\Connector;
use AppBundle\Connector\ConnectorThemes;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;

class ProcessModificationTest extends TestCase
{

    protected $root;

    protected $successBody;

    protected $failBody;

    public function setUp()
    {
        parent::setUp();
        $this->root = __DIR__.'/../';
        if (!file_exists($this->root.'/sitefacotry.yml')) {
            copy($this->root.'/sitefactory.default.yml', $this->root.'/sitefactory.yml');
        }
        $this->successBody = file_get_contents(__DIR__.'/Mocks/processModificationSuccess.json');
        $this->failBody = file_get_contents(__DIR__.'/Mocks/pingFail.json');
    }

    public function testProcessSuccess()
    {
        $mock = new MockHandler(
            [
            new Response(200, [], $this->successBody),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $connector = new Connector($client);
        $connectorThemes = new ConnectorThemes($connector);

        $this->assertTrue($connectorThemes->processModification() === 'The request to process theme notification has been accepted.');
    }

    public function testProcessFail()
    {
        $mock = new MockHandler(
            [
            new Response(403, [], $this->failBody),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $connector = new Connector($client);
        $connectorThemes = new ConnectorThemes($connector);

        $this->assertTrue($connectorThemes->processModification() === 'Access denied');
    }

    public function tearDown()
    {
        parent::tearDown();
        unlink($this->root.'/sitefactory.yml');
    }
}
