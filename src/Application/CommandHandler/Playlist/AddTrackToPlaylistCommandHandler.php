<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Playlist;

use App\Application\Command\Playlist\AddTrackToPlaylistCommand;
use Doctrine\ORM\EntityManagerInterface;

final readonly class AddTrackToPlaylistCommandHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {}

    public function __invoke(AddTrackToPlaylistCommand $command): void
    {
        $track = $command->getTrack();
        $playlist = $command->getPlaylist();

        if ($playlist->hasTrack($track)) {
            $playlist->removeTrack($track);
        } else {
            $playlist->addTrack($track);
        }

        $this->entityManager->flush();
    }
}
