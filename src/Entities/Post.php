<?php declare(strict_types=1);

namespace Sihae\Entities;

use Sihae\Slugifier;
use Doctrine\ORM\Mapping as ORM;
use Sihae\Entities\Traits\Timestamps;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @var string
     */
    protected $slug;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    protected $body;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     *
     * @var User
     */
    protected $user;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="users")
     * @ORM\JoinTable(
     *  name="post_tag",
     *  joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     *
     * @var Collection
     */
    protected $tags;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $is_page = false;

    /**
     * Initialise the $tags property on creation
     */
    public function __construct(string $title, string $body, User $user)
    {
        $this->title = $title;
        $this->body = $body;
        $this->user = $user;
        $this->slug = Slugifier::slugify($title);

        $this->tags = new ArrayCollection();
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
     * @return bool
     */
    public function isPage() : bool
    {
        return $this->is_page;
    }

    /**
     * @return Collection
     */
    public function getTags() : Collection
    {
        return $this->tags;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle(string $title) : void
    {
        $this->title = $title;
    }

    /**
     * @param string $body
     * @return void
     */
    public function setBody(string $body) : void
    {
        $this->body = $body;
    }

    /**
     * @param bool $isPage
     * @return void
     */
    public function setIsPage(bool $isPage) : void
    {
        $this->is_page = $isPage;
    }

    /**
     * Regenerate the slug to "ensure" uniqueness
     *
     * This doesn't *actually* ensure anything and will need to be updated if
     * this is to be used in situations where multiple users can be creating posts
     *
     * @return void
     */
    public function regenerateSlug() : void
    {
        $this->slug = Slugifier::slugify($this->title . ' ' . time());
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function addTag(Tag $tag) : void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addPost($this);
        }
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function removeTag(Tag $tag) : void
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removePost($this);
        }
    }

    /**
     * @return void
     */
    public function clearTags() : void
    {
        $this->tags = new ArrayCollection();
    }
}
