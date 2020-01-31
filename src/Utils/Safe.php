<?php declare(strict_types=1);

namespace Sihae\Utils;

final class Safe
{
    /**
     * @template T
     * @param string $key
     * @param array<string, T>|object|null $maybeArray
     * @param T $default
     * @return T
     */
    public static function get(string $key, $maybeArray, $default)
    {
        if ($maybeArray === null || !is_array($maybeArray)) {
            return $default;
        }

        return $maybeArray[$key] ?? $default;
    }
}
