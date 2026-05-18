<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\User;

use App\Application\Command\User\CreateUserCommand;
use App\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class CreateUserCommandHandler
{
    public function __construct(
        #[Autowire('%media.default_profile_picture%')]
        private string $defaultProfilePicture,
        public EntityManagerInterface $entityManager,
        public UserPasswordHasherInterface $passwordHasher,
    )
    {}

    public function handle(CreateUserCommand $command): User
    {
        $user = new User();

        $user->setEmail($command->email);
        $user->setUsername($command->username);
        $user->setProfilePicture($this->defaultProfilePicture);

        $hashedPassword = $this->passwordHasher
            ->hashPassword(
                $user, $command->password
            );

        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return $user;
    }
}
