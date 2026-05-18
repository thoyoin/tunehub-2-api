<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Playlist;

use App\Application\Command\Playlist\UpdatePlaylistCommand;
use App\Infrastructure\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;

final readonly class UpdatePlaylistCommandHandler
{
    public function __construct(
        private PlaylistRepository $playlistRepository,
        private EntityManagerInterface $entityManager,
        private FilesystemOperator $minioStorage,
    )
    {}

    public function __invoke(UpdatePlaylistCommand $command): void
    {
        $playlist = $this->playlistRepository->find($command->playlistId);

        $playlist->setTitle($command->title);
        $playlist->setDescription($command->description);

        if ($command->cover) {
            $fileName = uniqid('cover_', true)
                . '.'
                . $command->cover->guessExtension();

            $filePath = 'covers/' . $fileName;

            $stream = fopen($command->cover->getPathname(), 'r');

            $this->minioStorage->writeStream($filePath, $stream);

            $url = $this->minioStorage->publicUrl($filePath);

            if (is_resource($stream)) {
                fclose($stream);
            }

            $playlist->setCover($url);
        }

        $this->entityManager->flush();
    }
}
