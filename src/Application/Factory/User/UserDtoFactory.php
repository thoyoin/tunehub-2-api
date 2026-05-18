<?php

declare(strict_types=1);

namespace App\Application\Factory\User;

use App\Application\DTO\User\UserDto;
use App\Domain\Entity\User;

class UserDtoFactory
{
    public function create(User $user): UserDto
    {
        return new UserDto(
            id: $user->getId(),
            username: $user->getUsername(),
            slug: $user->getSlug(),
            email: $user->getEmail(),
            profilePicture: $user->getProfilePicture(),
            role: $user->getRole(),
        );
    }
}
