<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function existsByEmail(string $email): bool;

    public function existsByUsername(string $username): bool;

    public function getOneById(int $id): User;
}
