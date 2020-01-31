<?php declare(strict_types=1);

namespace Sihae\Formatters;

interface Formatter
{
    /**
     * Format the given array of data somehow
     *
     * @param array<mixed> $data
     * @return array<mixed>
     */
    public function format(array $data) : array;
}
