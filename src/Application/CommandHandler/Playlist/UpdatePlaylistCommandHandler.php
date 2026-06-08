<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Playlist;

use App\Application\Command\Media\DeleteFileCommand;
use App\Application\Command\Playlist\UpdatePlaylistCommand;
use App\Domain\Entity\Playlist;
use App\Domain\Repository\PlaylistRepositoryInterface;
use App\Infrastructure\Repository\PlaylistRepository;
use App\Infrastructure\Service\MinioService;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class UpdatePlaylistCommandHandler
{
    public function __construct(
        private PlaylistRepositoryInterface $playlistRepository,
        private MinioService $minioService,
        private MessageBusInterface $messageBus,
    )
    {}

    public function __invoke(UpdatePlaylistCommand $command): void
    {
        $playlist = $command->getPlaylist();

        $oldCover = $playlist->getCoverUrl();

        $playlist->update($command->getTitle(), $command->getDescription());

        if ($command->getCover() instanceof UploadedFile) {
            $url = $this->minioService->storeCover($command->getCover());

            $playlist->setCoverUrl($url);

            if ($oldCover !== '') {
                $this->messageBus->dispatch(new DeleteFileCommand($oldCover));
            }
        }

        $this->playlistRepository->save($playlist);
    }
}
