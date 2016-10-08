<?php

namespace Sihae\Tests\Unit\Formatters;

use DateTime;
use Carbon\Carbon;
use Prophecy\Prophet;
use Sihae\Entities\Post;
use PHPUnit\Framework\TestCase;
use Sihae\Formatters\ArchiveFormatter;

class ArchiveFormatterTest extends TestCase
{
    public function testItDoesNothingToEmptyArrays()
    {
        $archiveFormatter = new ArchiveFormatter();

        $expected = [];

        $this->assertSame($expected, $archiveFormatter->format([]));
    }

    public function testItSubdividesAnArrayOfPostsByTheYearTheyWereCreated()
    {
        $prophet = new Prophet();

        $date1 = Carbon::instance(new DateTime('2015-03-01'));
        $date2 = Carbon::instance(new DateTime('2016-03-01'));
        $date3 = Carbon::instance(new DateTime('2012-03-01'));
        $date4 = Carbon::instance(new DateTime('2015-06-01'));

        $post1 = $prophet->prophesize(Post::class);
        $post1->getDateCreated()->willReturn($date1);

        $post2 = $prophet->prophesize(Post::class);
        $post2->getDateCreated()->willReturn($date2);

        $post3 = $prophet->prophesize(Post::class);
        $post3->getDateCreated()->willReturn($date3);

        $post4 = $prophet->prophesize(Post::class);
        $post4->getDateCreated()->willReturn($date4);

        $data = [$post1->reveal(), $post2->reveal(), $post3->reveal(), $post4->reveal()];

        $archiveFormatter = new ArchiveFormatter();

        $expected = [
            '2015' => [$post1->reveal(), $post4->reveal()],
            '2016' => [$post2->reveal()],
            '2012' => [$post3->reveal()],
        ];

        $this->assertSame($expected, $archiveFormatter->format($data));
    }
}
