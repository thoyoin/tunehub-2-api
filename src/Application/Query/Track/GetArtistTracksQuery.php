<?php

declare(strict_types=1);

namespace App\Application\Query\Track;

use App\Domain\Entity\User;

class GetArtistTracksQuery implements \App\Application\Query\QueryInterface
{
    public function __construct(
        public User $artist,
    )
    {}
}
