<?php declare(strict_types=1);

namespace Sihae\Repositories;

use Sihae\Entities\Tag;
use Sihae\Entities\Post;

interface TagRepository
{
    public function findBySlug(string $slug) : ?Tag;

    /**
     * @return array<Tag>
     */
    public function findAllOrderedByUsage() : array;

    /**
     * @param array<int> $existingTags
     * @param array<string> $newTags
     * @return array<Tag>
     */
    public function findAll(array $existingTags, array $newTags) : array;

    /**
     * @return array<Tag>
     */
    public function findAllAsArray() : array;

    /**
     * @param Post $post
     * @return array<Tag>
     */
    public function findAllForPostAsArray(Post $post) : array;
}
