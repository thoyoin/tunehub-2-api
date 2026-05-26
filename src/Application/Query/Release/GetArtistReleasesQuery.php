<?php

declare(strict_types=1);

namespace App\Application\Query\Release;

use App\Domain\Entity\User;

class GetArtistReleasesQuery
{
    public function __construct(
        public User $artist
    )
    {}
}
