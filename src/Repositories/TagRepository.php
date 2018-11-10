<?php declare(strict_types=1);

namespace Sihae\Repositories;

use Sihae\Entities\Tag;
use Doctrine\ORM\EntityManager;

class TagRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getAllOrderedByUsage() : array
    {
        $dql =
            'SELECT t, p
             FROM Sihae\Entities\Tag t
             JOIN t.posts p';

        $tags = $this->entityManager->createQuery($dql)->getResult();

        /**
         * @todo Use database to order tags by post count
         */
        usort($tags, function (Tag $a, Tag $b) : int {
            return $b->getPosts()->count() <=> $a->getPosts()->count();
        });

        return $tags;
    }

    /**
     * @param array $existingTags
     * @param array $newTags
     * @return array
     */
    public function getAll(array $existingTags, array $newTags) : array
    {
        return array_merge(
            $this->find($existingTags),
            $this->findOrCreate($newTags)
        );
    }

    /**
     * Find tags from the given list of IDs
     *
     * @param array $tagIds
     * @return array
     */
    private function find(array $tagIds) : array
    {
        $query = $this->entityManager->createQuery(
            'SELECT t
             FROM Sihae\Entities\Tag t
             WHERE t.id IN (:tagIds)'
        );

        $query->setParameter(':tagIds', $tagIds);

        return $query->getResult();
    }

    /**
     * Find or create tags from the given list of names
     *
     * @param array $tagNames
     * @return array
     */
    private function findOrCreate(array $tagNames) : array
    {
        $repository = $this->entityManager->getRepository(Tag::class);

        return array_map(function (string $name) use ($repository) : Tag {
            // check for an existing tag with this name first
            if (!$tag = $repository->findOneBy(['name' => $name])) {
                $tag = new Tag($name);

                $this->entityManager->persist($tag);
            }

            return $tag;
        }, $tagNames);
    }
}
