<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Track;

use App\Application\Query\Track\CheckTrackPresenceQuery;
use App\Infrastructure\Repository\PlaylistRepository;

final readonly class CheckTrackPresenceQueryHandler
{
    public function __construct(
        private PlaylistRepository $playlistRepository,
    )
    {}

    public function __invoke(CheckTrackPresenceQuery $query): array
    {
        return $this->playlistRepository->findTrackPresenceForUser($query->getUser(), $query->getTrackIds());
    }
}
