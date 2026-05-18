<?php

declare(strict_types=1);

namespace App\Application\DTO\User;

use App\Domain\ValueObject\UserRole;
use JsonSerializable;

final readonly class UserDto implements JsonSerializable
{
    public function __construct(
        private ?int $id,
        private string $username,
        private string $slug,
        private string $email,
        private string $profilePicture,
        private ?UserRole $role,
    )
    {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getProfilePicture(): string
    {
        return $this->profilePicture;
    }

    public function getRole(): ?UserRole
    {
        return $this->role;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'slug' => $this->getSlug(),
            'email' => $this->getEmail(),
            'profilePicture' => $this->getProfilePicture(),
            'role' => $this->getRole(),
        ];
    }
}
