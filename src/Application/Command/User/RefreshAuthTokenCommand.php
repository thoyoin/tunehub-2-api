<?php

declare(strict_types=1);

namespace App\Application\Command\User;

use App\Application\DTO\User\UserDto;

final readonly class RefreshAuthTokenCommand
{
    public function __construct(
        private string $accessToken,
        private string $refreshToken,
        private UserDto $user,
    )
    {}

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getUser(): UserDto
    {
        return $this->user;
    }
}
