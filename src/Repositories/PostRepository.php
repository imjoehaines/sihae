<?php declare(strict_types=1);

namespace Sihae\Repositories;

use Sihae\Entities\Post;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectRepository;

class PostRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $repository;

    /**
     * @param EntityManager $entityManager
     * @param ObjectRepository $repository
     */
    public function __construct(
        EntityManager $entityManager,
        ObjectRepository $repository
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function findAllOrderedByDateCreated() : array
    {
        return $this->repository->findBy(['is_page' => false], ['date_created' => 'DESC']);
    }
}