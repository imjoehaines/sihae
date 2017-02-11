<?php declare(strict_types=1);

namespace Sihae\Formatters;

use Sihae\Entities\Post;

/**
 * Formatter for an array of posts for use on the Archive page
 */
class ArchiveFormatter implements Formatter
{
    /**
     * Format the given single dimentional array of Posts into a multi dimentional
     * array organised by year. No sorting is done because this should be done
     * using Doctrine & the database, rather than in code
     *
     * @param array $data
     * @return array
     */
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
