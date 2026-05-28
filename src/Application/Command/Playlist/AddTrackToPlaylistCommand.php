<?php

declare(strict_types=1);

namespace App\Application\Command\Playlist;

use App\Domain\Entity\Playlist;
use App\Domain\Entity\Track;

final readonly class AddTrackToPlaylistCommand
{
    public function __construct(
        private Playlist $playlist,
        private Track $track
    )
    {}

    public function getPlaylist(): Playlist
    {
        return $this->playlist;
    }

    public function getTrack(): Track
    {
        return $this->track;
    }
}
