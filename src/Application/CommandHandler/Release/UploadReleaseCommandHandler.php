<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Release;

use App\Application\Command\Release\UploadReleaseCommand;
use App\Domain\Entity\Release;
use App\Domain\Entity\Track;
use App\Domain\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Service\MinioService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UploadReleaseCommandHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private MinioService $minioService,
        private \getID3 $getID3,
        private EntityManagerInterface $entityManager,
    )
    {}

    public function __invoke(UploadReleaseCommand $command): void
    {
        $user = $this->userRepository->find($command->getArtistId());

        if (!$user instanceof User) {
            throw new \DomainException('User not found');
        }

        $this->entityManager->wrapInTransaction(function () use ($user, $command): void {
            $release = new Release();

            $release->setTitle($command->getReleaseTitle());
            $release->setArtist($user);
            $release->setReleaseDate($command->getReleaseDate());
            $release->setReleaseType($command->getType());

            $coverUrl = $this->minioService->storeCover($command->getCover());

            $release->setCoverUrl($coverUrl);

            foreach ($command->getAudioFiles() as $index => $file) {
                $title = $command->getTitles()[$index];
                $fileInfo = $this->getID3->analyze($file->getPathname());

                $duration = (int) round($fileInfo['playtime_seconds']);
                $audioUrl = $this->minioService->storeTrack($file);

                $track = new Track();
                $track->setTitle($title);
                $track->setAudioUrl($audioUrl);
                $track->setDuration($duration);
                $track->setArtist($user);
                $track->setRelease($release);
                $track->setPosition($index + 1);
                $track->setCoverUrl($release->getCoverUrl());
                $track->setReleaseDate($release->getReleaseDate());

                $release->addTrack($track);
            }

            $this->entityManager->persist($release);
            $this->entityManager->flush();
        });
    }
}
