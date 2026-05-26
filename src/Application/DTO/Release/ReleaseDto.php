<?php

declare(strict_types=1);

namespace App\Application\DTO\Release;

use App\Application\DTO\Track\TrackDto;
use App\Application\DTO\User\UserDto;
use App\Domain\ValueObject\ReleaseStatus;
use App\Domain\ValueObject\ReleaseType;

final readonly class ReleaseDto
{

    /**
     * @param array<int, TrackDto> $tracks
     */
    public function __construct(
        private int $id,
        private string $title,
        private UserDto $artist,
        private ReleaseType $releaseType,
        private string $coverUrl,
        private string $duration,
        private string $releaseDate,
        private ReleaseStatus $status,
        private array $tracks,
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

    public function getArtist(): UserDto
    {
        return $this->artist;
    }

    public function getReleaseType(): ReleaseType
    {
        return $this->releaseType;
    }

    public function getCoverUrl(): string
    {
        return $this->coverUrl;
    }

    public function getDuration(): string
    {
        return $this->duration;
    }

    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    public function getStatus(): ReleaseStatus
    {
        return $this->status;
    }


    /**
     * @return array<int, TrackDto>
     */
    public function getTracks(): array
    {
        return $this->tracks;
    }
}
