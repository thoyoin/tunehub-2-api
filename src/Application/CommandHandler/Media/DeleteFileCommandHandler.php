<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Media;

use App\Application\Command\Media\DeleteFileCommand;
use App\Infrastructure\Service\MinioService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteFileCommandHandler
{
    public function __construct(
        private MinioService $minioService,
    )
    {}

    public function __invoke(DeleteFileCommand $command): void
    {
        $this->minioService->destroyFile($command->getFileUrl());
    }
}
