<?php

declare(strict_types=1);

namespace App\Application\DTO\User;

final readonly class UserPreviewDto
{
    public function __construct(
        private int $userId,
        private string $username,
        private string $profilePicture,
    )
    {}

    public function getUserId(): int
    {
        return $this->userId;
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
