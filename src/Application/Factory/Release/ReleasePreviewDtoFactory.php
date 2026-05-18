<?php

declare(strict_types=1);

namespace App\Application\Factory\Release;

use App\Application\DTO\Release\ReleasePreviewDto;
use App\Application\Factory\User\UserDtoFactory;
use App\Domain\Entity\Release;

final readonly class ReleasePreviewDtoFactory
{
    public function __construct(
        private UserDtoFactory $userDtoFactory,
    )
    {}

    public function create(Release $release): ReleasePreviewDto
    {
        return new ReleasePreviewDto(
            id: $release->getId(),
            title: $release->getTitle(),
            artist: $this->userDtoFactory->create($release->getArtist()),
            release_type: $release->getReleaseType(),
            cover_url: $release->getCoverUrl(),
        );
    }
}
