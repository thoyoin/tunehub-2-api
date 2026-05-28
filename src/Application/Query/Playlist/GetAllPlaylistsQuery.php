<?php

declare(strict_types=1);

namespace App\Application\Query\Playlist;

use App\Domain\Entity\User;

final readonly class GetAllPlaylistsQuery
{
    public function __construct(
        private User $user,
    )
    {}

    public function getUser(): User{
        return $this->user;
    }
}
