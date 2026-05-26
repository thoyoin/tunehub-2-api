<?php

declare(strict_types=1);

namespace App\Application\DTO\Release;

use App\Application\DTO\Track\TrackDto;
use App\Application\DTO\User\UserDto;
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
        private ReleaseType $release_type,
        private string $cover_url,
        private ?\DateTimeImmutable $release_date,
        private string $status,
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
        return $this->release_type;
    }

    public function getCoverUrl(): string
    {
        return $this->cover_url;
    }

    public function getReleaseDate(): ?\DateTimeImmutable
    {
        return $this->release_date;
    }

    public function getStatus(): string
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
