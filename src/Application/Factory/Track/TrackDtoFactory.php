<?php

declare(strict_types=1);

namespace App\Application\Factory\Track;

use App\Application\DTO\Track\TrackDto;
use App\Application\Factory\Release\ReleasePreviewDtoFactory;
use App\Application\Factory\User\UserDtoFactory;
use App\Domain\Entity\Track;

readonly class TrackDtoFactory
{
    public function __construct(
        private UserDtoFactory $userDtoFactory,
        private ReleasePreviewDtoFactory $releasePreviewDtoFactory,
    )
    {}

    public function create(Track $track): TrackDto
    {
        return new TrackDto(
            id: $track->getId(),
            title: $track->getTitle(),
            artist: $this->userDtoFactory->create($track->getArtist()),
            release: $this->releasePreviewDtoFactory->create($track->getRelease()),
            coverUrl: $track->getCoverUrl(),
            duration: $track->getFormattedDuration(),
            audioUrl: $track->getAudioUrl(),
            releaseDate: $track->getFormattedReleaseDate(),
            position: $track->getPosition(),
        );
    }
}
