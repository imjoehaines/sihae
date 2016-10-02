<?php

namespace Sihae;

use Doctrine\ORM\EntityManager;

class PostRepository
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll() : array
    {
        return $this->entityManager->getRepository(Post::class)->findAll();
    }

    public function findBySlug(string $slug) : Post
    {
        $post = $this->entityManager->getRepository(Post::class)->findOneBy(['slug' => $slug]);

        if (!$post) {
            throw new PostNotFoundException('No post found with slug ' . $slug);
        }

        return $post;
    }
}
