<?php

declare(strict_types=1);

namespace App\Application\DTO\Auth;

class RefreshTokenPayloadDto
{
    public function __construct(
        private int $sub,
        private string $email,
        private string $type,
        private int $iat,
        private int $exp,
    )
    {}

    public function getSub(): int
    {
        return $this->sub;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getIat(): int
    {
        return $this->iat;
    }

    public function getExp(): int
    {
        return $this->exp;
    }
}
