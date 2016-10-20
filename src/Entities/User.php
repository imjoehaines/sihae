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
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @var string
     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=256)
     * @var string
     */
    protected $token;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $is_admin = false;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="user")
     * @var Collection
     */
    protected $posts;

    /**
     * Initialise posts as an empty ArrayCollection
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection;
    }

    /**
     * @return integer
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getToken() : string
    {
        return $this->token;
    }

    /**
     * @return boolean
     */
    public function getIsAdmin() : bool
    {
        return $this->is_admin;
    }

    /**
     * @return Collection
     */
    public function getPosts() : Collection
    {
        return $this->posts;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username) : User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password) : User
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }

    /**
     * @param string $token
     * @return User
     */
    public function setToken(string $token) : User
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @param boolean $isAdmin
     * @return User
     */
    public function setIsAdmin(bool $isAdmin) : User
    {
        $this->is_admin = $isAdmin;

        return $this;
    }
}
