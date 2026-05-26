<?php

declare(strict_types=1);

namespace App\Application\Factory\Release;

use App\Application\DTO\Release\ReleaseDto;
use App\Application\DTO\Track\TrackDto;
use App\Application\Factory\Track\TrackDtoFactory;
use App\Application\Factory\User\UserDtoFactory;
use App\Domain\Entity\Release;

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
            tracks: $this->createTrackDtos($release),
        );
    }

    /**
     * @return array<int, TrackDto>
     */
    public function createTrackDtos(Release $release): array
    {
        $trackDtos = [];

        foreach ($release->getTracks() as $track) {
            $trackDtos[] = $this->trackDtoFactory->create($track);
        }

        return $trackDtos;
    }
}
