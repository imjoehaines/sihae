<?php declare(strict_types=1);

namespace Sihae\Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;

use Sihae\Entities\Post;
use Sihae\Entities\User;
use Sihae\Formatters\ArchiveFormatter;

class ArchiveFormatterTest extends TestCase
{
    /**
     * @return void
     */
    public function testItDoesNothingToEmptyArrays() : void
    {
        $formatter = new ArchiveFormatter();

        $this->assertSame([], $formatter->format([]));
    }

    /**
     * @return void
     */
    public function testItFormatsArraysOfNewPostsIntoArraysIndexedByThisYear() : void
    {
        $formatter = new ArchiveFormatter();

        $post1 = new Post('', '', new User());
        $post1->onPrePersist();

        $post2 = new Post('', '', new User());
        $post2->onPrePersist();

        $data = [
            $post1,
            $post2,
        ];

        $actual = $formatter->format($data);

        $expected = [
            date('Y') => [
                $post1,
                $post2,
            ],
        ];

        $this->assertSame($expected, $actual);
    }
}
