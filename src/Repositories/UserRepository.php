<?php declare(strict_types=1);

namespace Sihae\Repositories;

use Sihae\Entities\User;
use Doctrine\ORM\EntityManager;

class UserRepository
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
        return $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
    }

    /**
     * @param string $token
     * @return User|null
     */
    public function findByToken(string $token) : ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['token' => $token]);
    }
}
