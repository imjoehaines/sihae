<?php

declare(strict_types=1);

namespace Sihae\Repositories;

use Sihae\Entities\Post;
use Sihae\Entities\Tag;

interface TagRepository
{
    public function findBySlug(string $slug): ?Tag;

    /**
     * @return array<Tag>
     */
    public function findAllOrderedByUsage(): array;

    /**
     * @param array<int> $existingTagIds an array of tag IDs that should already exist
     * @param array<string> $newTags an array of new tag names to create
     * @return array<Tag>
     */
    public function findAll(array $existingTagIds, array $newTags): array;

    /**
     * @return array<string, int|string>
     */
    public function findAllAsArray(): array;

    /**
     * @param Post $post
     * @return array<string, int|string>
     */
    public function findAllForPostAsArray(Post $post): array;
}
