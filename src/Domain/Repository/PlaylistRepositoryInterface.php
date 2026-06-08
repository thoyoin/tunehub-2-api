<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Playlist;
use App\Domain\Entity\User;

interface PlaylistRepositoryInterface
{
    /**
     * @param array<int, string> $trackIds
     * @return array<int|string, array<int>>
     */
    public function findTrackPresenceForUser(User $user, array $trackIds): array;

    public function delete(Playlist $playlist): void;

    public function save(Playlist $playlist): void;
}
