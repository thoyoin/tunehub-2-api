<?php

declare(strict_types=1);

namespace App\Application\Command\Playlist;

use App\Domain\ValueObject\PlaylistVisibility;

class CreatePlaylistCommand
{
    public function __construct(
        public int $userId,
        public string $title = 'My Playlist',
        public string $coverUrl = '',
        public PlaylistVisibility $visibility = PlaylistVisibility::Public,
    )
    {}
}
