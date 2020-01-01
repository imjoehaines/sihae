<?php declare(strict_types=1);

namespace Sihae\Repositories\Doctrine;

use Sihae\Entities\User;
use Doctrine\ORM\EntityManager;
use Sihae\Repositories\UserRepository;
use Doctrine\Common\Persistence\ObjectRepository;

class DoctrineUserRepository implements UserRepository
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
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(User::class);
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
        /** @var User|null $user */
        $user = $this->repository->findOneBy(['username' => $username]);

        return $user;
    }

    /**
     * @param string $token
     * @return User|null
     */
    public function findByToken(string $token) : ?User
    {
        /** @var User|null $user */
        $user = $this->repository->findOneBy(['token' => $token]);

        return $user;
    }
}
