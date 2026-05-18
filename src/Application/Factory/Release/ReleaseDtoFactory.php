<?php

declare(strict_types=1);

namespace App\Application\Factory\Release;

use App\Application\DTO\Release\ReleaseDto;
use App\Application\Factory\Track\TrackDtoFactory;
use App\Application\Factory\User\UserDtoFactory;
use App\Domain\Entity\Release;
use App\Domain\Entity\Track;

readonly class ReleaseDtoFactory
{
    public function __construct(
        private TrackDtoFactory $trackDtoFactory,
        private UserDtoFactory $userDtoFactory,
    )
    {}

    public function create(Release $release): ReleaseDto
    {
        return new ReleaseDto(
            id: $release->getId(),
            title: $release->getTitle(),
            artist: $this->userDtoFactory->create($release->getArtist()),
            release_type: $release->getReleaseType(),
            cover_url: $release->getCoverUrl(),
            release_date: $release->getReleaseDate(),
            status: $release->getStatus(),
            tracks: array_map(
                fn (Track $track) => $this->trackDtoFactory->create($track),
                $release->getTracks()->toArray(),
            ),
        );
    }
}
