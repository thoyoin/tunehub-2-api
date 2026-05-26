<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Release;

use App\Application\Command\Release\DeleteReleaseCommand;
use App\Infrastructure\Repository\ReleaseRepository;

final readonly class DeleteReleaseCommandHandler
{
    public function __construct(
        private ReleaseRepository $releaseRepository
    )
    {}

    public function __invoke(DeleteReleaseCommand $command): void
    {
        $release = $command->getRelease();

        $this->releaseRepository->delete($release);
    }
}
