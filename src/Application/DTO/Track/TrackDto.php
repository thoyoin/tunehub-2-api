<?php

declare(strict_types=1);

namespace App\Application\DTO\Track;

use App\Application\DTO\Release\ReleaseDto;
use App\Application\DTO\Release\ReleasePreviewDto;
use App\Application\DTO\User\UserDto;

final readonly class TrackDto
{
    public function __construct(
        private int $id,
        private string $title,
        private UserDto $artist,
        private ReleaseDto|ReleasePreviewDto $release,
        private string $coverUrl,
        private string $duration,
        private string $audioUrl,
        private \DateTimeImmutable $releaseDate,
        private int $position,
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

    public function getRelease(): ReleaseDto|ReleasePreviewDto
    {
        return $this->release;
    }

    public function getCoverUrl(): string
    {
        return $this->coverUrl;
    }

    public function getDuration(): string
    {
        return $this->duration;
    }

    public function getAudioUrl(): string
    {
        return $this->audioUrl;
    }

    public function getReleaseDate(): \DateTimeImmutable
    {
        return $this->releaseDate;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}
