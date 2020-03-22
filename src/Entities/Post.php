<?php

declare(strict_types=1);

namespace Sihae\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sihae\Entities\Traits\Timestamps;
use Sihae\Utils\Slugifier;

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
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    private string $title;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @var string
     */
    private string $slug;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private string $body;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     *
     * @var User
     */
    private User $user;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="users")
     * @ORM\JoinTable(
     *  name="post_tag",
     *  joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     *
     * @var Collection<int, Tag>
     */
    private Collection $tags;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private bool $is_page = false;

    public function __construct(string $title, string $body, User $user)
    {
        $this->title = $title;
        $this->body = $body;
        $this->user = $user;
        $this->slug = Slugifier::slugify($title);

        $this->tags = new ArrayCollection();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function isPage(): bool
    {
        return $this->is_page;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function setIsPage(bool $isPage): void
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
    public function regenerateSlug(): void
    {
        $this->slug = Slugifier::slugify($this->title . ' ' . time());
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addPost($this);
        }
    }

    public function removeTag(Tag $tag): void
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removePost($this);
        }
    }

    public function clearTags(): void
    {
        $this->tags = new ArrayCollection();
    }
}
