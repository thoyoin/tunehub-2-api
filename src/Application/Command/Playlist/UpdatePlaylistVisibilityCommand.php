<?php

declare(strict_types=1);

namespace App\Application\Command\Playlist;

use App\Domain\Entity\Playlist;
use App\Domain\ValueObject\PlaylistVisibility;

final readonly class UpdatePlaylistVisibilityCommand implements \App\Application\Command\CommandInterface
{
    public function __construct(
        private Playlist $playlist,
        private PlaylistVisibility $visibility,
    )
    {}

    public function getPlaylist(): Playlist
    {
        return $this->playlist;
    }

    public function getVisibility(): PlaylistVisibility
    {
        return $this->visibility;
    }
}
