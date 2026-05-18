<?php

declare(strict_types=1);

namespace App\Application\Query\Playlist;

final readonly class GetPlaylistByIdQuery
{
    public function __construct(
        public int $playlistId
    )
    {}
}
