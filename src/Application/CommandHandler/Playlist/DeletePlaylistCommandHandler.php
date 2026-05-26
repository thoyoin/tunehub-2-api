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
        if (!$command->playlist instanceof Playlist) {
            throw new \DomainException('Playlist not found');
        }

        $this->entityManager->remove($command->playlist);

        $this->entityManager->flush();
    }
}
