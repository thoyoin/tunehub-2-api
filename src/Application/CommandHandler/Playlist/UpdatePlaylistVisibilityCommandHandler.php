<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Playlist;

use App\Application\Command\Playlist\UpdatePlaylistVisibilityCommand;
use App\Domain\ValueObject\PlaylistVisibility;
use App\Infrastructure\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UpdatePlaylistVisibilityCommandHandler
{
    public function __construct(
        private PlaylistRepository $playlistRepository,
        private EntityManagerInterface $entityManager,
    )
    {}

    public function __invoke(UpdatePlaylistVisibilityCommand $command): PlaylistVisibility
    {
        $playlist = $this->playlistRepository->find($command->playlistId);


        $playlist->setVisibility($command->visibility);

        $this->entityManager->flush();

        return $playlist->getVisibility();
    }
}
