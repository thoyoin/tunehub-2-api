<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Playlist;

use App\Application\Command\Playlist\UpdatePlaylistVisibilityCommand;
use App\Domain\ValueObject\PlaylistVisibility;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UpdatePlaylistVisibilityCommandHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {}

    public function __invoke(UpdatePlaylistVisibilityCommand $command): PlaylistVisibility
    {
        $playlist = $command->getPlaylist();

        $playlist->setVisibility($command->getVisibility());

        $this->entityManager->flush();

        return $playlist->getVisibility();
    }
}
