<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\User;

use App\Application\Command\User\CreateUserCommand;
use App\Application\Factory\User\UserDtoFactory;
use App\Domain\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class CreateUserCommandHandler
{
    public function __construct(
        #[Autowire('%media.default_profile_picture%')]
        private string $defaultProfilePicture,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
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

        return $user;
    }
}
