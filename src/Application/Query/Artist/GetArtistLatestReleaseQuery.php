<?php

declare(strict_types=1);

namespace App\Application\Query\Artist;

use App\Domain\Entity\User;

final readonly class GetArtistLatestReleaseQuery implements \App\Application\Query\QueryInterface
{
    public function __construct(
        private User $artist
    )
    {}

    public function getArtist(): User
    {
        return $this->artist;
    }
}
