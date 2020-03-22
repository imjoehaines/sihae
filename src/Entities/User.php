<?php

declare(strict_types=1);

namespace Sihae\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sihae\Entities\Traits\Timestamps;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks
 */
class User
{
    use Timestamps;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     *
     * @var string
     */
    private string $username;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=256)
     *
     * @var string
     */
    private string $token;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private bool $is_admin = false;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="user")
     *
     * @var Collection<int, Post>
     */
    private Collection $posts;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;

        $this->updateToken();
        $this->setPassword($password);

        $this->posts = new ArrayCollection();
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function login(string $password): bool
    {
        if (!password_verify($password, $this->password)) {
            return false;
        }

        // if the password was hashed with an old algorithm, re-hash it
        if (password_needs_rehash($this->password, PASSWORD_DEFAULT)) {
            $this->setPassword($password);
        }

        $this->updateToken();

        return true;
    }

    private function updateToken(): void
    {
        $this->token = bin2hex(random_bytes(128));
    }

    private function setPassword(string $password): void
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if ($hash === false) {
            throw new \RuntimeException('Unable to hash the given password');
        }

        $this->password = $hash;
    }
}
