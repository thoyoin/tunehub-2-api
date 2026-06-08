<?php

declare(strict_types=1);

namespace App\Application\Command\User;

final readonly class RefreshAuthTokenCommand implements \App\Application\Command\CommandInterface
{
    public function __construct(
        private string $refreshToken
    )
    {}

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
