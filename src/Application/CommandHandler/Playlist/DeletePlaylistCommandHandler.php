<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Playlist;

use App\Application\Command\Playlist\DeletePlaylistCommand;
use App\Domain\Entity\Playlist;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DeletePlaylistCommandHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {}

    public function __invoke(DeletePlaylistCommand $command): void
    {
        $this->entityManager->remove($command->playlist);

        $this->entityManager->flush();
    }
}
