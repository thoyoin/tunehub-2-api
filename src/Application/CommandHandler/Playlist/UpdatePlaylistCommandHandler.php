<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Playlist;

use App\Application\Command\Playlist\UpdatePlaylistCommand;
use App\Domain\Entity\Playlist;
use App\Infrastructure\Repository\PlaylistRepository;
use App\Infrastructure\Service\MinioService;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UpdatePlaylistCommandHandler
{
    public function __construct(
        private PlaylistRepository $playlistRepository,
        private EntityManagerInterface $entityManager,
        private MinioService $minioService,
    )
    {}

    public function __invoke(UpdatePlaylistCommand $command): void
    {
        $playlist = $this->playlistRepository->find($command->getPlaylistId());

        if (!$playlist instanceof Playlist) {
            throw new \DomainException('Playlist not found');
        }

        if ($command->getTitle() !== null) {
            $playlist->setTitle($command->getTitle());
        }

        if ($command->getDescription() !== null) {
            $playlist->setDescription($command->getDescription());
        }

        if ($command->getCover() instanceof UploadedFile) {
            $url = $this->minioService->storeCover($command->getCover());

            $playlist->setCoverUrl($url);
        }

        $this->entityManager->flush();
    }
}
