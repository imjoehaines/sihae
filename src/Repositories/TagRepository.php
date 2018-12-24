<?php declare(strict_types=1);

namespace Sihae\Repositories;

use Sihae\Entities\Tag;
use Sihae\Entities\Post;

interface TagRepository
{
    public function findBySlug(string $slug) : ?Tag;

    public function findAllOrderedByUsage() : array;

    public function findAll(array $existingTags, array $newTags) : array;

    public function findAllAsArray() : array;

    public function findAllForPostAsArray(Post $post) : array;
}
