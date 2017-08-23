<?php
namespace tests;

use AppBundle\Connector\Connector;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{

    protected $root;

    protected $successBody;

    protected $failBody;

    protected $backup_id;

    public function setUp()
    {
        parent::setUp();
        $this->root = __DIR__.'/../';
        copy($this->root.'/sitefactory.default.yml', $this->root.'/sitefactory.yml');
        $this->successBody = file_get_contents(__DIR__.'/Mocks/urlSuccess.json');
        $this->failBody = file_get_contents(__DIR__.'/Mocks/pingFail.json');
        $this->backup_id = '1';
    }

    public function testUrlSuccess()
    {
        $mock = new MockHandler(
            [
            new Response(200, [], $this->successBody),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $connector = new Connector($client);

        $this->assertTrue(is_string($connector->getBackupURL($this->backup_id)));
    }

    public function testUrlFail()
    {
        $mock = new MockHandler(
            [
            new Response(403, [], $this->failBody),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $connector = new Connector($client);

        $this->assertTrue($connector->getBackupURL($this->backup_id) === 'Access denied');
    }

    public function tearDown()
    {
        parent::tearDown();
        unlink($this->root.'/sitefactory.yml');
    }
}
