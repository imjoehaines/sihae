<?php

declare(strict_types=1);

namespace Sihae\Repositories;

use Sihae\Entities\Post;

interface PostRepository
{
    public function save(Post $post): void;

    public function delete(Post $post): void;

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return array<Post>
     */
    public function findAllOrderedByDateCreated(?int $limit = null, ?int $offset = null): array;

    public function findOneBySlug(string $slug): ?Post;

    /**
     * @param string $slug
     * @param int $limit
     * @param int $offset
     * @return array<Post>
     */
    public function findAllTagged(string $slug, int $limit, int $offset): array;

    public function count(): int;

    public function countTagged(string $slug): int;
}
