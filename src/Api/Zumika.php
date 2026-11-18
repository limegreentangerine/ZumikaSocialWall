<?php

namespace ZumikaSocialWall\Api;

use Core;
use Monolog\Logger;
use ClassKit\Api\Enum\RequestMethod;
use ClassKit\Api\ConnectionController;
use ZumikaSocialWall\Config\ZumikaEnv;
use ZumikaSocialWall\Log\ZumikaLogger;

/**
 * Talks to the Zumika API with the key from the site's .env file.
 */
class Zumika extends ConnectionController
{
    /**
     * @var Logger
     */
    protected $logger;

    public function __construct()
    {
        $this->logger = Core::make(ZumikaLogger::class)->getLogger();

        parent::__construct(
            ZumikaEnv::urlOrDefault(),
            'json',
            ['Authorization' => 'Bearer ' . ZumikaEnv::apiKey(), 'Accept' => 'application/json'],
        );
    }

    /**
     * The workspace's feeds, or an error message to show the editor.
     *
     * @return array{feeds: array<int, array{key: string, name: string}>, error: string}
     */
    public function getFeeds(): array
    {
        if (ZumikaEnv::apiKey() === null) {
            return ['feeds' => [], 'error' => t('Add %s to the site\'s .env file.', ZumikaEnv::API_KEY)];
        }

        $response = $this->makeRequest(RequestMethod::GET->value, '/api/v1/feeds');
        $body = json_decode($response->getBody(), true);

        if ($response->getStatusCode() !== 200 || !is_array($body) || empty($body['success'])) {
            $message = is_array($body) ? ($body['error'] ?? $body['message'] ?? null) : null;
            $message = is_string($message) && $message !== '' ? $message : t('Zumika did not accept the API key.');
            $this->logger->error(sprintf('Unable to list Zumika feeds: [%d] %s', $response->getStatusCode(), $message));

            return ['feeds' => [], 'error' => $message];
        }

        $feeds = [];
        foreach ($body['feeds'] ?? [] as $feed) {
            if (is_array($feed) && isset($feed['public_key'], $feed['name'])) {
                $feeds[] = ['key' => (string) $feed['public_key'], 'name' => (string) $feed['name']];
            }
        }

        return ['feeds' => $feeds, 'error' => ''];
    }
}
