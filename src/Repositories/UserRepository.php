<?php declare(strict_types=1);

namespace Sihae\Repositories;

use Sihae\Entities\User;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectRepository;

class UserRepository
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

    /**
     * @param User $user
     * @return void
     */
    public function save(User $user) : void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username) : ?User
    {
        return $this->repository->findOneBy(['username' => $username]);
    }

    /**
     * @param string $token
     * @return User|null
     */
    public function findByToken(string $token) : ?User
    {
        return $this->repository->findOneBy(['token' => $token]);
    }
}
