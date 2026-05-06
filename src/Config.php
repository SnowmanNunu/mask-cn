<?php

declare(strict_types=1);

namespace MaskCn;

class Config
{
    /** @var array<string, mixed> */
    private static $config = [];

    public static function set(array $config): void
    {
        self::$config = $config;
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return self::$config[$key] ?? $default;
    }

    public static function reset(): void
    {
        self::$config = [];
    }
}
