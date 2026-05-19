<?php

namespace App\Domain\Entity;

use App\Infrastructure\Repository\LibraryItemRepository;
use App\Domain\ValueObject\LibraryItemType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibraryItemRepository::class)]
class LibraryItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'libraryItems')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(enumType: LibraryItemType::class)]
    private LibraryItemType $itemType;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Playlist $playlist = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Release $release = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getItemType(): LibraryItemType
    {
        return $this->itemType;
    }

    public function setItemType(LibraryItemType $itemType): static
    {
        $this->itemType = $itemType;

        return $this;
    }

    public function getPlaylist(): ?Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(Playlist $playlist): static
    {
        $this->playlist = $playlist;
        $this->release = null;
        $this->itemType = LibraryItemType::Playlist;

        return $this;
    }

    public function getRelease(): ?Release
    {
        return $this->release;
    }

    public function setRelease(Release $release): static
    {
        $this->release = $release;
        $this->playlist = null;
        $this->itemType = LibraryItemType::Release;

        return $this;
    }

    public function isRelease(): bool
    {
        return $this->itemType === LibraryItemType::Release;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'user' => $this->getUser(),
            'itemType' => $this->getItemType(),
            'playlist' => $this->getPlaylist(),
            'release' => $this->getRelease(),
        ];
    }
}
