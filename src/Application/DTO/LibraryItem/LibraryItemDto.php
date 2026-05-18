<?php

declare(strict_types=1);

namespace App\Application\DTO\LibraryItem;

use App\Application\DTO\Playlist\PlaylistDto;
use App\Application\DTO\Release\ReleaseDto;
use App\Domain\ValueObject\LibraryItemType;

final readonly class LibraryItemDto
{
    public function __construct(
        private int $id,
        private LibraryItemType $itemType,
        private PlaylistDto|ReleaseDto $item,
    )
    {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getItemType(): LibraryItemType
    {
        return $this->itemType;
    }

    public function getItem(): PlaylistDto|ReleaseDto
    {
        return $this->item;
    }
}
