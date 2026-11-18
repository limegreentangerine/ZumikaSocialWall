<?php

namespace ZumikaSocialWall\Config;

/**
 * The package's settings. They live in the site's .env file, not the database or file config.
 */
class ZumikaEnv
{
    public const API_KEY = 'ZUMIKA_API_KEY';
    public const URL = 'ZUMIKA_URL';

    /**
     * Used when ZUMIKA_URL is unset or is not a valid address.
     */
    public const DEFAULT_URL = 'https://zumika.app';

    private static function get(string $name): ?string
    {
        $value = $_ENV[$name] ?? $_SERVER[$name] ?? getenv($name);

        return is_string($value) && trim($value) !== '' ? trim($value) : null;
    }

    /**
     * The Zumika API key from the environment, or null when it is unset or blank.
     */
    public static function apiKey(): ?string
    {
        return self::get(self::API_KEY);
    }

    /**
     * The Zumika address (e.g. https://zumika.app) without a trailing slash, or null when it is unset or invalid.
     * The value ends up in a <script src> on every page with a wall, so only a plain http(s) origin and path is accepted.
     */
    public static function url(): ?string
    {
        $value = self::get(self::URL);

        if ($value === null || !preg_match('#^https?://[A-Za-z0-9.-]+(?::\d{1,5})?(?:/[A-Za-z0-9._~/-]*)?$#', $value)) {
            return null;
        }

        return rtrim($value, '/');
    }

    /**
     * The Zumika address to use: the configured one, or the default.
     */
    public static function urlOrDefault(): string
    {
        return self::url() ?? self::DEFAULT_URL;
    }
}
