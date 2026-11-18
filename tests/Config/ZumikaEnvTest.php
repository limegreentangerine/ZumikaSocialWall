<?php

namespace ZumikaSocialWall\Tests\Config;

use PHPUnit\Framework\TestCase;
use ZumikaSocialWall\Config\ZumikaEnv;
use PHPUnit\Framework\Attributes\DataProvider;

class ZumikaEnvTest extends TestCase
{
    public static function validUrlProvider(): array
    {
        return [
            'origin' => ['https://zumika.app', 'https://zumika.app'],
            'trailing slash' => ['https://zumika.app/', 'https://zumika.app'],
            'local port' => ['http://localhost:8080', 'http://localhost:8080'],
            'path' => [' https://eu.zumika.app/wall ', 'https://eu.zumika.app/wall'],
        ];
    }

    #[DataProvider('validUrlProvider')]
    public function testUrlAcceptsPlainAddresses(string $value, string $expected): void
    {
        $_ENV[ZumikaEnv::URL] = $value;

        $this->assertSame($expected, ZumikaEnv::url());
        $this->assertSame($expected, ZumikaEnv::urlOrDefault());
    }

    public static function invalidUrlProvider(): array
    {
        return [
            'blank' => [''],
            'no scheme' => ['zumika.app'],
            'javascript' => ['javascript:alert(1)'],
            'quote breakout' => ['https://zumika.app/"><script>alert(1)</script>'],
            'query' => ['https://zumika.app/?x=1'],
            'credentials' => ['https://user:pass@zumika.app'],
        ];
    }

    #[DataProvider('invalidUrlProvider')]
    public function testUrlRejectsInvalidValuesAndFallsBackToDefault(string $value): void
    {
        $_ENV[ZumikaEnv::URL] = $value;

        $this->assertNull(ZumikaEnv::url());
        $this->assertSame(ZumikaEnv::DEFAULT_URL, ZumikaEnv::urlOrDefault());
    }

    public function testApiKeyIsTrimmedAndNullWhenBlank(): void
    {
        $_ENV[ZumikaEnv::API_KEY] = '  zumika_abc ';
        $this->assertSame('zumika_abc', ZumikaEnv::apiKey());

        $_ENV[ZumikaEnv::API_KEY] = '   ';
        $this->assertNull(ZumikaEnv::apiKey());
    }

    protected function tearDown(): void
    {
        unset($_ENV[ZumikaEnv::URL], $_ENV[ZumikaEnv::API_KEY]);

        parent::tearDown();
    }
}
