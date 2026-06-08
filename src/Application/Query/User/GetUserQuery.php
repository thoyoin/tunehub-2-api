<?php

declare(strict_types=1);

namespace App\Application\Query\User;

use App\Domain\Entity\User;

final readonly class GetUserQuery implements \App\Application\Query\QueryInterface
{
    public function __construct(
        private User $user
    )
    {}

    public function getUser(): User
    {
        return $this->user;
    }
}
