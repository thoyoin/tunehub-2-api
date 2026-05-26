<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Release;

use App\Application\Command\Release\PublishReleaseCommand;
use App\Domain\ValueObject\ReleaseStatus;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PublishReleaseCommandHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {}

    public function __invoke(PublishReleaseCommand $command): void
    {
        $release = $command->getRelease();

        $release->setStatus(ReleaseStatus::PUBLISHED);

        $this->entityManager->persist($release);
        $this->entityManager->flush();
    }
}
