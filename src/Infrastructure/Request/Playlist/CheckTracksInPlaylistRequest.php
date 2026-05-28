<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Playlist;

class CheckTracksInPlaylistRequest
{
    public function __construct(
        public string $track_ids
    )
    {}
}
