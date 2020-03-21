<?php

declare(strict_types=1);

namespace Sihae\Utils;

final class Safe
{
    /**
     * @param string $key
     * @param array<string, string>|object|null $maybeArray
     * @param string $default
     * @return string
     */
    public static function getString(string $key, $maybeArray, string $default)
    {
        if (!is_array($maybeArray) || !isset($maybeArray[$key])) {
            return $default;
        }

        return $maybeArray[$key];
    }

    /**
     * @param string $key
     * @param array<string, array<mixed>>|object|null $maybeArray
     * @param array<mixed> $default
     * @return array<mixed>
     */
    public static function getArray(string $key, $maybeArray, array $default): array
    {
        if (!is_array($maybeArray) || !isset($maybeArray[$key])) {
            return $default;
        }

        return $maybeArray[$key];
    }
}
