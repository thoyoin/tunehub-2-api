<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Track;

interface TrackRepositoryInterface
{
    /**
     * @param int $artistId
     * @return array<Track>
     */
    public function getArtistTop(int $artistId): array;
}
