<?php declare(strict_types=1);

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
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     *
     * @var string
     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=256)
     *
     * @var string
     */
    protected $token;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $is_admin = false;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="user")
     *
     * @var Collection
     */
    protected $posts;

    /**
     * Initialise posts as an empty ArrayCollection
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @return int
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
     * @return bool
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
     * @return void
     */
    public function setUsername(string $username) : void
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     * @return void
     */
    public function setPassword(string $password) : void
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if ($hash === false) {
            throw new \RuntimeException('Unable to hash the given password');
        }

        $this->password = $hash;
    }

    /**
     * @param string $token
     * @return void
     */
    public function setToken(string $token) : void
    {
        $this->token = $token;
    }

    /**
     * @param bool $isAdmin
     * @return void
     */
    public function setIsAdmin(bool $isAdmin) : void
    {
        $this->is_admin = $isAdmin;
    }
}
