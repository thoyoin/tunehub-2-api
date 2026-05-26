<?php

declare(strict_types=1);

namespace App\Application\Query\Track;

use App\Domain\Entity\User;

class GetArtistTracksQuery
{
    public function __construct(
        public User $artist,
    )
    {}
}
