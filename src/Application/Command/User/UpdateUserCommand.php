<?php

declare(strict_types=1);

namespace App\Application\Command\User;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UpdateUserCommand
{
    public function __construct(
        private int $id,
        private ?string $username = null,
        private ?string $email = null,
        private ?UploadedFile $profilePicture = null,
    )
    {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getProfilePicture(): ?UploadedFile
    {
        return $this->profilePicture;
    }
}
