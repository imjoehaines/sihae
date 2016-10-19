<?php

namespace Sihae\Entities;

use Doctrine\ORM\Mapping as ORM;
use function Stringy\create as s;
use Sihae\Entities\Traits\Timestamps;

/**
 * @ORM\Entity
 * @ORM\Table(name="page")
 * @ORM\HasLifecycleCallbacks
 */
class Page
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
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    protected $slug;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $body;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @var User
     */
    protected $user;

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
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSlug() : string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * @return User
     */
    public function getUser() : User
    {
        return $this->user;
    }

    /**
     * @param string $title
     * @return Page
     */
    public function setTitle(string $title) : Page
    {
        $this->title = $title;

        if (!$this->slug) {
            $this->slug = (string) s($title)->slugify();
        }

        return $this;
    }

    /**
     * @param string $body
     * @return Page
     */
    public function setBody(string $body) : Page
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param User $user
     * @return Page
     */
    public function setUser(User $user) : Page
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Regenerate the slug to "ensure" uniqueness
     *
     * This doesn't *actually* ensure anything and will need to be updated if
     * this is to be used in situations where multiple users can be creating posts
     *
     * @return Page
     */
    public function regenerateSlug() : Page
    {
        $this->slug = (string) s($this->title . ' ' . time())->slugify();

        return $this;
    }
}
