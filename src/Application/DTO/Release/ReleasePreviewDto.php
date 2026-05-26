<?php

declare(strict_types=1);

namespace App\Application\DTO\Release;

use App\Application\DTO\User\UserPreviewDto;
use App\Domain\ValueObject\ReleaseStatus;
use App\Domain\ValueObject\ReleaseType;

final readonly class ReleasePreviewDto
{
    public function __construct(
        private int $id,
        private string $title,
        private UserPreviewDto $artist,
        private ReleaseType $releaseType,
        private string $releaseDate,
        private string $releaseDuration,
        private int $tracksCount,
        private string $coverUrl,
        private ReleaseStatus $status,
    )
    {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getArtist(): UserPreviewDto
    {
        return $this->artist;
    }

    public function getReleaseType(): ReleaseType
    {
        return $this->releaseType;
    }

    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    public function getReleaseDuration(): string
    {
        return $this->releaseDuration;
    }

    public function getTracksCount(): int
    {
        return $this->tracksCount;
    }

    public function getCoverUrl(): string
    {
        return $this->coverUrl;
    }

    public function getStatus(): ReleaseStatus
    {
        return $this->status;
    }
}
