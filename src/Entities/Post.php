<?php

namespace Sihae\Entities;

use DateTime;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use function Stringy\create as s;

/**
 * @ORM\Entity
 * @ORM\Table(name="post")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_created;

    public function __construct()
    {
        if (!$this->date_created) {
            $this->date_created = new DateTime();
        }
    }

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

    public function getDateCreated() : Carbon
    {
        return Carbon::instance($this->date_created);
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
}
