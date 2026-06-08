<?php

declare(strict_types=1);

namespace App\Application\DTO\User;

final readonly class UserPreviewDto
{
    public function __construct(
        private int $id,
        private string $username,
        private string $profilePicture,
    )
    {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getProfilePicture(): string
    {
        return $this->profilePicture;
    }
}
