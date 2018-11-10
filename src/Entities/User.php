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
    public function __construct(string $username, string $password, string $token)
    {
        $this->username = $username;
        $this->token = $token;

        $this->setPassword($password);

        $this->posts = new ArrayCollection();
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
    public function isAdmin() : bool
    {
        return $this->is_admin;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function isCorrectPassword(string $password) : bool
    {
        return password_verify($password, $this->password);
    }

    public function authenticated(string $password) : void
    {
        $this->rehash($password);
        $this->updateToken();
    }

    /**
     * @param string $password
     * @return void
     */
    private function rehash(string $password) : void
    {
        // if the password was hashed with an old algorithm, re-hash it
        if (password_needs_rehash($this->password, PASSWORD_DEFAULT)) {
            $this->setPassword($password);
        }
    }

    /**
     * @return void
     */
    private function updateToken() : void
    {
        $this->token = bin2hex(random_bytes(128));
    }

    /**
     * @param string $password
     * @return void
     */
    private function setPassword(string $password) : void
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if ($hash === false) {
            throw new \RuntimeException('Unable to hash the given password');
        }

        $this->password = $hash;
    }
}
