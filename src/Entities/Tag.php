<?php declare(strict_types=1);

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
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @var string
     */
    protected $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags")
     *
     * @var Collection
     */
    protected $posts;

    /**
     * Initialise the posts property on creation
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->slug = (string) s($name)->slugify();

        $this->posts = new ArrayCollection();
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
     * @return Collection
     */
    public function getPosts() : Collection
    {
        return $this->posts;
    }

    /**
     * @param Post $post
     * @return void
     */
    public function addPost(Post $post) : void
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->addTag($this);
        }
    }

    /**
     * @param Post $post
     * @return void
     */
    public function removePost(Post $post) : void
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            $post->removeTag($this);
        }
    }
}
