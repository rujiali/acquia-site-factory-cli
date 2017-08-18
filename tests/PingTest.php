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

class PingTest extends TestCase {

  protected $root;

  protected $successBody;

  protected $failBody;

  public function setUp() {
    parent::setUp();
    $this->root = __DIR__ . '/../';
    copy($this->root . '/sitefactory.default.yml', $this->root . '/sitefactory.yml');
    $this->successBody = file_get_contents(__DIR__ . '/Mocks/pingSuccess.json');
    $this->failBody = file_get_contents(__DIR__ . '/Mocks/pingFail.json');
  }

  public function testPingSuccess() {
    $mock = new MockHandler([
      new Response(200, [], $this->successBody),
    ]);
    $handler = HandlerStack::create($mock);
    $client = new Client(['handler' => $handler]);

    $connector = new Connector($this->root, $client);

    $this->assertTrue($connector->ping() === 'pong');
  }

  public function testPingFail() {
    $mock = new MockHandler([
      new Response(403, [], $this->failBody),
    ]);
    $handler = HandlerStack::create($mock);
    $client = new Client(['handler' => $handler]);

    $connector = new Connector($this->root, $client);

    $this->assertTrue($connector->ping() === 'Access denied');
  }

  public function tearDown() {
    parent::tearDown();
    unlink($this->root . '/sitefactory.yml');
  }
}
