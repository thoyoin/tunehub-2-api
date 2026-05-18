<?php

declare(strict_types=1);

namespace App\Application\Command\Playlist;

use App\Domain\ValueObject\PlaylistVisibility;

final readonly class UpdatePlaylistVisibilityCommand
{
    public function __construct(
        public int $playlistId,
        public PlaylistVisibility $visibility,
    )
    {}
}
