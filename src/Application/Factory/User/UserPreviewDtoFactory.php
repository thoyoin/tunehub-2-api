<?php

declare(strict_types=1);

namespace App\Application\Factory\User;

use App\Application\DTO\User\UserPreviewDto;
use App\Domain\Entity\User;

class UserPreviewDtoFactory
{
    public function create(User $user): UserPreviewDto
    {
        return new UserPreviewDto(
            $user->getId(),
            $user->getUsername(),
            $user->getProfilePicture(),
        );
    }
}
