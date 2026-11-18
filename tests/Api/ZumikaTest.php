<?php

namespace ZumikaSocialWall\Tests\Api;

use Monolog\Logger;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Monolog\Handler\TestHandler;
use ZumikaSocialWall\Api\Zumika;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Client as HttpClient;
use ZumikaSocialWall\Config\ZumikaEnv;
use Concrete\Core\Logging\LoggerFactory;
use Concrete\Core\Support\Facade\Facade;
use Concrete\Core\Application\Application;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Localization\Translator\TranslatorAdapterRepository;
use Concrete\Core\Localization\Translator\Adapter\Plain\TranslatorAdapterFactory;

class ZumikaTest extends TestCase
{
    private array $history = [];

    private TestHandler $logHandler;

    /**
     * Builds the API with the given key and a Guzzle client that replays $responses.
     */
    private function makeApi(array $responses, ?string $apiKey = 'zumika_test'): Zumika
    {
        if ($apiKey === null) {
            unset($_ENV[ZumikaEnv::API_KEY]);
        } else {
            $_ENV[ZumikaEnv::API_KEY] = $apiKey;
        }

        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));

        $api = new Zumika();
        // Test seam: swap the Guzzle client ConnectionController creates internally
        (fn(HttpClient $client) => $this->client = $client)->call($api, new HttpClient(['handler' => $stack]));

        return $api;
    }

    private function json(int $status, array $body): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($body));
    }

    public function testListsFeedsAndSendsBearerKey(): void
    {
        $result = $this->makeApi([$this->json(200, ['success' => true, 'feeds' => [
            ['public_key' => 'abc123', 'name' => 'Main wall'],
            ['name' => 'missing key is skipped'],
        ]])])->getFeeds();

        $this->assertSame(['feeds' => [['key' => 'abc123', 'name' => 'Main wall']], 'error' => ''], $result);
        $request = $this->history[0]['request'];
        $this->assertSame('https://zumika.app/api/v1/feeds', (string) $request->getUri());
        $this->assertSame('Bearer zumika_test', $request->getHeaderLine('Authorization'));
    }

    public function testReturnsApiErrorAndLogsIt(): void
    {
        $result = $this->makeApi([$this->json(401, ['success' => false, 'error' => 'Invalid key'])])->getFeeds();

        $this->assertSame(['feeds' => [], 'error' => 'Invalid key'], $result);
        $this->assertTrue($this->logHandler->hasErrorThatContains('[401] Invalid key'));
    }

    public function testMissingKeySkipsTheRequest(): void
    {
        $result = $this->makeApi([], null)->getFeeds();

        $this->assertSame([], $result['feeds']);
        $this->assertStringContainsString(ZumikaEnv::API_KEY, $result['error']);
        $this->assertSame([], $this->history);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $app = new Application();
        $app->instance('app', $app);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);

        $localization = new Localization();
        $localization->setTranslatorAdapterRepository(new TranslatorAdapterRepository(new TranslatorAdapterFactory()));
        $localization->setActiveContext(Localization::CONTEXT_UI);
        $app->instance(Localization::class, $localization);

        $this->logHandler = new TestHandler();
        $loggerFactory = $this->createMock(LoggerFactory::class);
        $loggerFactory->method('createLogger')->willReturnCallback(fn(string $channel) => new Logger($channel, [$this->logHandler]));
        $app->instance(LoggerFactory::class, $loggerFactory);
    }

    protected function tearDown(): void
    {
        unset($_ENV[ZumikaEnv::API_KEY], $_ENV[ZumikaEnv::URL]);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);

        parent::tearDown();
    }
}
