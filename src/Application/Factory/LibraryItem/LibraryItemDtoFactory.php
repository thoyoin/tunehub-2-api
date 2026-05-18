<?php

declare(strict_types=1);

namespace App\Application\Factory\LibraryItem;

use App\Application\DTO\LibraryItem\LibraryItemDto;
use App\Application\DTO\Playlist\PlaylistDto;
use App\Application\DTO\Release\ReleaseDto;
use App\Application\Factory\Playlist\PlaylistDtoFactory;
use App\Application\Factory\Release\ReleaseDtoFactory;
use App\Domain\Entity\LibraryItem;
use App\Domain\ValueObject\LibraryItemType;

readonly class LibraryItemDtoFactory
{
    public function __construct(
        private PlaylistDtoFactory $playlistDtoFactory,
        private ReleaseDtoFactory $releaseDtoFactory,
    )
    {}

    public function create(LibraryItem $libraryItem): LibraryItemDto
    {
        return new LibraryItemDto(
            id: $libraryItem->getId(),
            itemType: $libraryItem->getItemType(),
            item: $this->resolveType($libraryItem),
        );
    }

    public function resolveType(LibraryItem $libraryItem): PlaylistDto|ReleaseDto
    {
        if ($libraryItem->getItemType() === LibraryItemType::Playlist) {
            return $this->playlistDtoFactory->create($libraryItem->getPlaylist());
        }

        if ($libraryItem->getItemType() === LibraryItemType::Release) {
            return $this->releaseDtoFactory->create($libraryItem->getRelease());
        }

        throw new \RuntimeException('Library item type not supported');
    }
}
