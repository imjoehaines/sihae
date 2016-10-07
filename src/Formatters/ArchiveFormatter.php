<?php

namespace Sihae\Formatters;

use Sihae\Entities\Post;

class ArchiveFormatter implements Formatter
{
    public function format(array $data) : array
    {
        return array_reduce($data, function (array $carry, Post $post) : array {
            $date = $post->getDateCreated()->format('Y');

            if (isset($carry[$date])) {
                $carry[$date] = array_merge($carry[$date], [$post]);
            } else {
                $carry[$date] = [$post];
            }

            return $carry;
        }, []);
    }
}
