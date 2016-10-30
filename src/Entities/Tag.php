<?php

namespace Sihae\Entities;

use Doctrine\ORM\Mapping as ORM;
use function Stringy\create as s;
use Sihae\Entities\Traits\Timestamps;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 * @ORM\HasLifecycleCallbacks
 */
class Tag
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
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    protected $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags")
     * @var Post
     */
    protected $posts;

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
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug() : string
    {
        return $this->slug;
    }

    /**
     * @param string $title
     * @return Tag
     */
    public function setName(string $name)
    {
        $this->name = $name;

        if (!$this->slug) {
            $this->slug = (string) s($name)->slugify();
        }

        return $this;
    }

    public function getPosts() : Collection
    {
        return $this->posts;
    }

    /**
     * @param Post $post
     * @return Post
     */
    public function addPost(Post $post)
    {
        if ($this->posts->contains($post)) {
            return;
        }

        $this->posts->add($post);
        $post->addTag($this);

        return $this;
    }

    /**
     * @param Post $post
     * @return Post
     */
    public function removePost(Post $post) : Post
    {
        if (!$this->posts->contains($post)) {
            return;
        }

        $this->posts->removeElement($post);
        $post->removeTag($this);

        return $this;
    }
}
