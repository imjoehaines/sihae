<?php

declare(strict_types=1);

namespace Sihae;

final class Slugifier
{
    public static function slugify(string $string): string
    {
        $parts = explode(' ', $string);

        $alphanumericOnly = array_map(function (string $word): string {
            return preg_replace('/[^\w]/', '', $word) ?? '';
        }, $parts);

        return strtolower(implode('-', $alphanumericOnly));
    }
}
