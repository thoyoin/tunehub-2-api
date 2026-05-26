<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Release;

use App\Application\Command\Release\UpdateReleaseCommand;
use App\Infrastructure\Service\MinioService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UpdateReleaseCommandHandler
{
    public function __construct(
        private MinioService $minioService,
        private EntityManagerInterface $entityManager,
    )
    {}

    public function __invoke(UpdateReleaseCommand $command): void
    {
        $release = $command->getRelease();

        $this->entityManager->wrapInTransaction(function () use ($command, $release) {
            if ($command->getReleaseTitle() !== null) {
                $release->setTitle($command->getReleaseTitle());
            }

            if ($command->getCover() instanceof UploadedFile) {
                $url = $this->minioService->storeCover($command->getCover());

                $release->setCoverUrl($url);
            }

            $this->entityManager->flush();
        });
    }
}
