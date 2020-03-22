<?php

declare(strict_types=1);

namespace Sihae\Utils;

final class Slugifier
{
    public static function slugify(string $string): string
    {
        $parts = explode(' ', $string);

        $alphanumericOnly = array_map(
            fn (string $word): string => preg_replace('/\W/', '', $word) ?? '',
            $parts
        );

        return strtolower(implode('-', $alphanumericOnly));
    }
}
