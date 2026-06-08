<?php

declare(strict_types=1);

namespace App\Application\Command\Playlist;

use App\Domain\Entity\Playlist;

final readonly class DeletePlaylistCommand implements \App\Application\Command\CommandInterface
{
    public function __construct(
        private Playlist $playlist,
    )
    {}

    public function getPlaylist(): Playlist
    {
        return $this->playlist;
    }
}
