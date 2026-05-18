<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\DTO\Auth\RefreshTokenPayloadDto;
use App\Domain\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final readonly class RefreshTokenService
{
    public function __construct(
        private string $refreshSecret,
    ) {}

    public function create(User $user): string
    {
        $payload = [
            'sub' => $user->getId(),
            'email' => $user->getUserIdentifier(),
            'type' => 'refresh',
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 3),
        ];

        return JWT::encode($payload, $this->refreshSecret, 'HS256');
    }

    public function parse(string $token): RefreshTokenPayloadDto
    {
        $decoded = JWT::decode(
            $token,
            new Key($this->refreshSecret, 'HS256')
        );

        if (!isset($decoded->sub, $decoded->email, $decoded->type, $decoded->iat, $decoded->exp)) {
            throw new \RuntimeException('Invalid refresh token payload.');
        }

        if (!is_int($decoded->sub)) {
            throw new \RuntimeException('Invalid refresh token subject.');
        }

        if (!is_string($decoded->email)) {
            throw new \RuntimeException('Invalid refresh token email.');
        }

        if (!is_string($decoded->type)) {
            throw new \RuntimeException('Invalid refresh token type.');
        }

        if (!is_int($decoded->iat)) {
            throw new \RuntimeException('Invalid refresh token issued-at timestamp.');
        }

        if (!is_int($decoded->exp)) {
            throw new \RuntimeException('Invalid refresh token expiration timestamp.');
        }

        if ($decoded->type !== 'refresh') {
            throw new \RuntimeException('Invalid token type.');
        }

        return new RefreshTokenPayloadDto(
            $decoded->sub,
            $decoded->email,
            $decoded->type,
            $decoded->iat,
            $decoded->exp
        );
    }
}
