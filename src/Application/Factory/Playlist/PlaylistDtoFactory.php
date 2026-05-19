<?php

declare(strict_types=1);

namespace App\Application\Factory\Playlist;

use App\Application\DTO\Playlist\PlaylistDto;
use App\Application\DTO\Playlist\PlaylistPreviewDto;
use App\Application\DTO\Track\TrackDto;
use App\Application\Factory\Track\TrackDtoFactory;
use App\Application\Factory\User\UserDtoFactory;
use App\Domain\Entity\Playlist;
use App\Domain\Entity\Track;

final readonly class PlaylistDtoFactory
{
    public function __construct(
        private TrackDtoFactory $trackDtoFactory,
        private UserDtoFactory $userDtoFactory,
    )
    {}

    public function create(Playlist $playlist): PlaylistDto
    {
        return new PlaylistDto(
            id: $playlist->getId(),
            title: $playlist->getTitle(),
            slug: $playlist->getSlug(),
            description: $playlist->getDescription(),
            coverUrl: $playlist->getCoverUrl(),
            itemType: $playlist->getItemType(),
            owner: $this->userDtoFactory->create($playlist->getOwner()),
            visibility: $playlist->getVisibility(),
            tracks: $this->createTrackDtos($playlist),
            createdAt: $playlist->getCreatedAt()
        );
    }

    public function createPreview(Playlist $playlist): PlaylistPreviewDto
    {
        return new PlaylistPreviewDto(
            id: $playlist->getId(),
            title: $playlist->getTitle(),
            slug: $playlist->getSlug(),
            coverUrl: $playlist->getCoverUrl(),
            itemType: $playlist->getItemType(),
            createdAt: $playlist->getCreatedAt(),
            tracks: $this->createTrackDtos($playlist),
        );
    }

    /**
     * @return array<int, TrackDto>
     */
    public function createTrackDtos(Playlist $playlist): array
    {
        $trackDtos = [];

        foreach ($playlist->getTracks() as $track) {
            $trackDtos[] = $this->trackDtoFactory->create($track);
        }

        return $trackDtos;
    }
}
