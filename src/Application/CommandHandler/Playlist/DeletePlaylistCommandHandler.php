<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Playlist;

use App\Application\Command\Playlist\DeletePlaylistCommand;
use App\Domain\Entity\Playlist;
use App\Infrastructure\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DeletePlaylistCommandHandler
{
    public function __construct(
        private PlaylistRepository $playlistRepository,
        private EntityManagerInterface $entityManager,
    )
    {}

    public function __invoke(DeletePlaylistCommand $command): void
    {
        $playlist = $this->playlistRepository->find($command->playlistId);

        if (!$playlist instanceof Playlist) {
            throw new \DomainException('Playlist not found');
        }

        $this->entityManager->remove($playlist);

        $this->entityManager->flush();
    }
}
