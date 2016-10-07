<?php

namespace Sihae\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
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
    protected $is_admin;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_created;

    public function __construct()
    {
        if (!$this->date_created) {
            $this->date_created = new DateTime();
        }

        $this->is_admin = false;
    }

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
