<?php

namespace Sihae\Entities;

use Doctrine\ORM\Mapping as ORM;
use Sihae\Entities\Traits\Timestamps;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_admin = false;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="user")
     */
    protected $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getId() : int
    {
        return $this->id;
    }

    public function getUsername() : string
    {
        return $this->username;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getIsAdmin() : bool
    {
        return $this->is_admin;
    }

    public function getPosts() : Collection
    {
        return $this->posts;
    }

    public function setUsername(string $username) : User
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword(string $password) : User
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }

    public function setIsAdmin(bool $isAdmin) : User
    {
        $this->is_admin = $isAdmin;

        return $this;
    }
}
