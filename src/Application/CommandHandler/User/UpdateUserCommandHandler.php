<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\User;

use App\Application\Command\User\UpdateUserCommand;
use App\Domain\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Service\MinioService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class UpdateUserCommandHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private MinioService $minioService,
    )
    {}

    public function __invoke(UpdateUserCommand $command): void
    {
        $user = $this->userRepository->find($command->getId());

        if (!$user instanceof User) {
            throw new \DomainException('User not found');
        }

        if ($command->getUsername() !== null) {
            $user->setUsername($command->getUsername());
        }

        if ($command->getEmail() !== null) {
            $user->setEmail($command->getEmail());
        }

        if ($command->getProfilePicture() instanceof UploadedFile) {
            $url = $this->minioService->storeProfilePicture($command->getProfilePicture());

            $user->setProfilePicture($url);
        }

        $this->entityManager->flush();
    }
}
