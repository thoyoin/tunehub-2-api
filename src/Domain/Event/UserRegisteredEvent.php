<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

final class UserRegisteredEvent extends Event
{
    public function __construct(
        readonly private User $user,
    )
    {}

    public function getUser(): User
    {
        return $this->user;
    }
}
