<?php

declare(strict_types=1);

namespace App\Application\Query\Playlist;

use App\Domain\Entity\Playlist;

final readonly class GetPlaylistQuery
{
    public function __construct(
        public Playlist $playlist
    )
    {}
}
