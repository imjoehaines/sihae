<?php

namespace Sihae\Entities;

use Doctrine\ORM\Mapping as ORM;
use function Stringy\create as s;
use Sihae\Entities\Traits\Timestamps;

/**
 * @ORM\Entity
 * @ORM\Table(name="post")
 * @ORM\HasLifecycleCallbacks
 */
class Post
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $is_page = false;

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
     * @return boolean
     */
    public function getIsPage() : bool
    {
        return $this->is_page;
    }

    /**
     * @param string $title
     * @return Post
     */
    public function setTitle(string $title) : Post
    {
        $this->title = $title;

        if (!$this->slug) {
            $this->slug = (string) s($title)->slugify();
        }

        return $this;
    }

    /**
     * @param string $body
     * @return Post
     */
    public function setBody(string $body) : Post
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param User $user
     * @return Post
     */
    public function setUser(User $user) : Post
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param boolean $isPage
     * @return Post
     */
    public function setIsPage(bool $isPage) : Post
    {
        $this->is_page = $isPage;

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
    public function regenerateSlug() : Post
    {
        $this->slug = (string) s($this->title . ' ' . time())->slugify();

        return $this;
    }
}
