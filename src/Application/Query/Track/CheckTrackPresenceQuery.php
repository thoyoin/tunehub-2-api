<?php

declare(strict_types=1);

namespace App\Application\Query\Track;

use App\Domain\Entity\User;

final readonly class CheckTrackPresenceQuery
{
    public function __construct(
        private User $user,
        private array $trackIds,
    )
    {}

    public function getUser(): User
    {
        return $this->user;
    }

    public function getTrackIds(): array
    {
        return $this->trackIds;
    }
}
