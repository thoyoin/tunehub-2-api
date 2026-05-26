<?php

declare(strict_types=1);

namespace App\Application\Command\Playlist;

use App\Domain\Entity\Playlist;

class DeletePlaylistCommand
{
    public function __construct(
        public Playlist $playlist,
    )
    {}
}
