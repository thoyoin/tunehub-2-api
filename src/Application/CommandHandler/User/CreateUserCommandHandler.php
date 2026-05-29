<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\User;

use App\Application\Command\User\CreateUserCommand;
use App\Domain\Entity\User;
use App\Domain\Event\UserRegisteredEvent;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class CreateUserCommandHandler
{
    public function __construct(
        #[Autowire('%media.default_profile_picture%')]
        private string $defaultProfilePicture,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
        private EventDispatcherInterface $eventDispatcher,
    )
    {}

    public function handle(CreateUserCommand $command): User
    {
        $user = new User();

        if ($this->userRepository->existsByEmail($command->getEmail())) {
            throw new \DomainException('Email already exists.');
        }

        $username = strtolower($command->getUsername());

        if ($this->userRepository->existsByUsername($username)) {
            throw new \DomainException('Username already exists.');
        }

        $this->entityManager->wrapInTransaction(function () use ($user, $command): void {
            $user->setEmail(strtolower($command->getEmail()));
            $user->setUsername($command->getUsername());
            $user->setProfilePicture($this->defaultProfilePicture);

            $hashedPassword = $this->passwordHasher
                ->hashPassword(
                    $user, $command->getPassword()
                );

            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);

            $this->entityManager->flush();

            $this->eventDispatcher->dispatch(new UserRegisteredEvent($user));
        });

        return $user;
    }
}
