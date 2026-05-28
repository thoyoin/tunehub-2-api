<?php

declare(strict_types=1);

namespace App\Application\Query\Track;

use App\Domain\Entity\User;

final readonly class CheckTrackPresenceQuery
{
    /**
     * @param array<int|string> $trackIds
     */
    public function __construct(
        private User $user,
        private array $trackIds,
    )
    {}

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return array<int|string>
     */
    public function getTrackIds(): array
    {
        return $this->trackIds;
    }
}
