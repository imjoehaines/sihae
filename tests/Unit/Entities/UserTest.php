<?php

namespace Sihae\Tests\Entities;

use Carbon\Carbon;
use Sihae\Entities\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testSetUsername()
    {
        $user = new User();
        $user->setUsername('bob');

        $this->assertSame('bob', $user->getUsername());
    }

    public function testSetPasswordHashesGivenPassword()
    {
        $user = new User();
        $user->setPassword('password');

        $this->assertTrue(password_verify('password', $user->getPassword()));
    }

    public function testAUserIsNotAnAdminByDefault()
    {
        $user = new User();

        $this->assertFalse($user->getIsAdmin());
    }

    public function testAUserCanBePromotedToAnAdmin()
    {
        $user = new User();
        $user->setIsAdmin(true);

        $this->assertTrue($user->getIsAdmin());
    }

    public function testAUserHasNoPostsWhenFirstInstantiated()
    {
        $user = new User();

        $this->assertEmpty($user->getPosts());
    }

    public function testItGeneratesCreatedAndModifiedDatesWhenPersisted()
    {
        $user = new User();
        $user->onPrePersist();

        $this->assertInstanceOf(Carbon::class, $user->getDateCreated());
        $this->assertInstanceOf(Carbon::class, $user->getDateModified());
    }

    public function testItRegeneratesModifiedDatesWhenUpdated()
    {
        $user = new User();
        $user->onPrePersist();

        $inital = $user->getDateModified();

        $user->onPreUpdate();

        $afterUpdate = $user->getDateModified();

        $this->assertTrue($afterUpdate !== $inital);
        $this->assertSame($afterUpdate, $inital->max($afterUpdate));
    }

    public function testItHasNotBeenModifiedWhenBothDatesAreTheSame()
    {
        $user = new User();
        $user->onPrePersist();

        $this->assertFalse($user->hasBeenModified());
    }
}
