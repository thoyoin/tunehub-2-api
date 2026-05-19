<?php

declare(strict_types=1);

namespace App\Application\DTO\Playlist;

use App\Application\DTO\Track\TrackDto;
use App\Application\DTO\User\UserDto;
use App\Domain\ValueObject\PlaylistVisibility;
use DateTimeImmutable;

final readonly class PlaylistDto
{
    /**
     * @param array<int, TrackDto> $tracks
     */
    public function __construct(
        private int $id,
        private string $title,
        private string $slug,
        private ?string $description,
        private string $coverUrl,
        private string $itemType,
        private UserDto $owner,
        private PlaylistVisibility $visibility,
        private array $tracks,
        private DateTimeImmutable $createdAt,
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCoverUrl(): string
    {
        return $this->coverUrl;
    }

    public function getItemType(): string
    {
        return $this->itemType;
    }

    public function getOwner(): UserDto
    {
        return $this->owner;
    }

    public function getVisibility(): PlaylistVisibility
    {
        return $this->visibility;
    }

    /**
     * @return array<int, TrackDto>
     */
    public function getTracks(): array
    {
        return $this->tracks;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
