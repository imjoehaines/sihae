<?php

declare(strict_types=1);

namespace Sihae\Repositories\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Sihae\Entities\Post;
use Sihae\Repositories\PostRepository;

final class DoctrinePostRepository implements PostRepository
{
    private EntityManager $entityManager;

    /**
     * @var ObjectRepository<Post>
     */
    private ObjectRepository $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Post::class);
    }

    public function save(Post $post): void
    {
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    public function delete(Post $post): void
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return array<Post>
     */
    public function findAllOrderedByDateCreated(?int $limit = null, ?int $offset = null): array
    {
        return $this->repository->findBy([], ['date_created' => 'DESC'], $limit, $offset);
    }

    public function findBySlug(string $slug): ?Post
    {
        return $this->repository->findOneBy(['slug' => $slug]);
    }

    /**
     * @param string $slug
     * @param int $limit
     * @param int $offset
     * @return array<Post>
     */
    public function findAllTagged(string $slug, int $limit, int $offset): array
    {
        $dql =
            'SELECT p, t
             FROM Sihae\Entities\Post p
             JOIN p.tags t
             WHERE t.slug = :slug
             ORDER BY p.date_created DESC';

        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->setParameter(':slug', $slug);

        return $query->getResult();
    }

    public function count(): int
    {
        $query = $this->entityManager->createQuery(
            'SELECT COUNT(p.id)
             FROM Sihae\Entities\Post p'
        );

        return (int) $query->getSingleScalarResult();
    }

    public function countTagged(string $slug): int
    {
        $query = $this->entityManager->createQuery(
            'SELECT COUNT(t.id)
             FROM Sihae\Entities\Post p
             JOIN p.tags t
             WHERE t.slug = :slug'
        )->setParameter(':slug', $slug);

        return (int) $query->getSingleScalarResult();
    }
}
