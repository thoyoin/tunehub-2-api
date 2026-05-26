<?php

declare(strict_types=1);

namespace App\Application\Factory\Release;

use App\Application\DTO\Release\ReleasePreviewDto;
use App\Application\Factory\User\UserPreviewDtoFactory;
use App\Domain\Entity\Release;
use Carbon\Carbon;

final readonly class ReleasePreviewDtoFactory
{
    public function __construct(
        private UserPreviewDtoFactory $dtoFactory,
    )
    {}

    public function create(Release $release): ReleasePreviewDto
    {
        return new ReleasePreviewDto(
            id: $release->getId(),
            title: $release->getTitle(),
            artist: $this->dtoFactory->create($release->getArtist()),
            releaseType: $release->getReleaseType(),
            releaseDate: Carbon::instance($release->getReleaseDate())->toFormattedDateString(),
            releaseDuration: $release->getDuration(),
            tracksCount: $release->getTracksCount(),
            coverUrl: $release->getCoverUrl(),
            status: $release->getStatus(),
        );
    }
}
