<?php

declare(strict_types=1);

namespace Sihae\Repositories\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Sihae\Entities\User;
use Sihae\Repositories\UserRepository;

final class DoctrineUserRepository implements UserRepository
{
    private EntityManager $entityManager;

    /**
     * @var ObjectRepository<User>
     */
    private ObjectRepository $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function findByUsername(string $username): ?User
    {
        return $this->repository->findOneBy(['username' => $username]);
    }

    public function findByToken(string $token): ?User
    {
        return $this->repository->findOneBy(['token' => $token]);
    }
}
