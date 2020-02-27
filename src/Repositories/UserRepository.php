<?php

declare(strict_types=1);

namespace Sihae\Repositories;

use Sihae\Entities\User;

interface UserRepository
{
    public function save(User $user): void;

    public function findByUsername(string $username): ?User;

    public function findByToken(string $token): ?User;
}
