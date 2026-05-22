<?php

declare(strict_types=1);

namespace App\Application\Command\Playlist;

use App\Domain\ValueObject\PlaylistVisibility;

final readonly class UpdatePlaylistVisibilityCommand
{
    public function __construct(
        private int $playlistId,
        private ?PlaylistVisibility $visibility,
    )
    {}

    public function getPlaylistId(): ?int
    {
        return $this->playlistId;
    }

    public function getVisibility(): PlaylistVisibility
    {
        return $this->visibility;
    }
}
