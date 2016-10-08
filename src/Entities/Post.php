<?php

namespace Sihae\Entities;

use Sihae\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use function Stringy\create as s;

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
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $slug;

    /**
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    public function getSummary() : string
    {
        return s($this->body)->safeTruncate(450, '…');
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getSlug() : string
    {
        return $this->slug;
    }

    public function getBody() : string
    {
        return $this->body;
    }

    public function getUser() : User
    {
        return $this->user;
    }

    public function setTitle(string $title) : Post
    {
        $this->title = $title;

        if (!$this->slug) {
            $this->slug = (string) s($title)->slugify();
        }

        return $this;
    }

    public function setBody(string $body) : Post
    {
        $this->body = $body;

        return $this;
    }

    public function setUser(User $user) : Post
    {
        $this->user = $user;

        return $this;
    }
}
