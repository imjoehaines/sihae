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

    public function save(User $user) : void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function findByUsername(string $username) : ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
    }

    public function findByToken(string $token) : ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['token' => $token]);
    }
}
