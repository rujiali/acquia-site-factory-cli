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

class SendNotificationTest extends TestCase
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
        $this->successBody = file_get_contents(__DIR__.'/Mocks/sendNotificationSuccess.json');
        $this->failBody = file_get_contents(__DIR__.'/Mocks/pingFail.json');
    }

    public function testSendSuccess()
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

        $this->assertTrue(isset($connectorThemes->sendNotification('site', 'modify')->scope));
    }

    public function testSendNotificationFail()
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

        $this->assertTrue($connectorThemes->sendNotification('site', 'modify') === 'Access denied');
    }

    public function tearDown()
    {
        parent::tearDown();
        unlink($this->root.'/sitefactory.yml');
    }
}
