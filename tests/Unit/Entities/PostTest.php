<?php

namespace Sihae\Tests\Entities;

use Carbon\Carbon;
use Sihae\Entities\Post;
use Sihae\Entities\User;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testSetTitleAlsoSetsSlug()
    {
        $post = new Post();
        $post->setTitle('hello');

        $this->assertSame('hello', $post->getTitle());
        $this->assertSame('hello', $post->getSlug());
    }

    public function testTitleGetsSlugified()
    {
        $post = new Post();
        $post->setTitle('hello hi howdy');

        $this->assertSame('hello-hi-howdy', $post->getSlug());
    }

    public function testSummaryIsGeneratedFromTheBody()
    {
        $post = new Post();
        $post->setBody('hello');

        $this->assertSame('hello', $post->getBody());
        $this->assertSame('hello', $post->getSummary());
    }

    public function testSummaryIsTruncatedTo450Characters()
    {
        $post = new Post();
        $post->setBody(str_repeat('abcd ', 100));

        $expected = str_repeat('abcd ', 89) . 'abcd…';

        $this->assertSame($expected, $post->getSummary());
    }

    public function testItGeneratesCreatedAndModifiedDatesWhenPersisted()
    {
        $post = new Post();
        $post->onPrePersist();

        $this->assertInstanceOf(Carbon::class, $post->getDateCreated());
        $this->assertInstanceOf(Carbon::class, $post->getDateModified());
    }

    public function testItRegeneratesModifiedDatesWhenUpdated()
    {
        $post = new Post();
        $post->onPrePersist();

        $inital = $post->getDateModified();

        $post->onPreUpdate();

        $afterUpdate = $post->getDateModified();

        $this->assertTrue($afterUpdate !== $inital);
        $this->assertSame($afterUpdate, $inital->max($afterUpdate));
    }

    public function testItHasNotBeenModifiedWhenBothDatesAreTheSame()
    {
        $post = new Post();
        $post->onPrePersist();

        $this->assertFalse($post->hasBeenModified());
    }

    public function testAPostBelongsToAUser()
    {
        $post = new Post();
        $user = new User();

        $post->setUser($user);

        $this->assertSame($user, $post->getUser());
    }
}
