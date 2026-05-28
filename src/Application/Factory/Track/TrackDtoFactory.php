<?php

declare(strict_types=1);

namespace App\Application\Factory\Track;

use App\Application\DTO\Track\TrackDto;
use App\Application\Factory\Release\ReleasePreviewDtoFactory;
use App\Application\Factory\User\UserPreviewDtoFactory;
use App\Domain\Entity\Track;
use Carbon\Carbon;

readonly class TrackDtoFactory
{
    public function __construct(
        private UserPreviewDtoFactory $userDtoFactory,
        private ReleasePreviewDtoFactory $releasePreviewDtoFactory,
    )
    {}

    public function create(Track $track, ?\DateTimeImmutable $addedAt = null): TrackDto
    {
        return new TrackDto(
            id: $track->getId(),
            title: $track->getTitle(),
            artist: $this->userDtoFactory->create($track->getArtist()),
            release: $this->releasePreviewDtoFactory->create($track->getRelease()),
            coverUrl: $track->getCoverUrl(),
            duration: $track->getFormattedDuration(),
            audioUrl: $track->getAudioUrl(),
            releaseDate: Carbon::instance($track->getReleaseDate())->toFormattedDateString(),
            position: $track->getPosition(),
            addedAgo: $addedAt !== null ? Carbon::instance($addedAt)->diffForHumans() : null,
        );
    }
}
