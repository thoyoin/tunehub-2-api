<?php

declare(strict_types=1);

namespace App\Application\DTO\Playlist;

use DateTimeImmutable;

final readonly class PlaylistPreviewDto
{
    public function __construct(
        private int $id,
        private string $title,
        private string $slug,
        private string $coverUrl,
        private string $itemType,
        private \DateTimeImmutable $createdAt,
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getCoverUrl(): string
    {
        return $this->coverUrl;
    }

    public function getItemType(): string
    {
        return $this->itemType;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getTracks(): array
    {
        return $this->tracks;
    }
}
