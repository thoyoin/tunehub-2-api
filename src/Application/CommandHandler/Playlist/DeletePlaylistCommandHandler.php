<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Playlist;

use App\Application\Command\Playlist\DeletePlaylistCommand;
use App\Domain\Entity\Playlist;
use App\Domain\Repository\PlaylistRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeletePlaylistCommandHandler
{
    public function __construct(
        private PlaylistRepositoryInterface $playlistRepository,
    )
    {}

    public function __invoke(DeletePlaylistCommand $command): void
    {
        $this->playlistRepository->delete($command->getPlaylist());
    }
}
