<?php

declare(strict_types=1);

namespace App\Application\Command\Playlist;

use App\Domain\Entity\Playlist;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UpdatePlaylistCommand
{
    public function __construct(
        private Playlist $playlist,
        private ?string $title = null,
        private ?string $description = null,
        private ?UploadedFile $cover = null,
    )
    {}

    public function getPlaylist(): Playlist
    {
        return $this->playlist;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCover(): ?UploadedFile
    {
        return $this->cover;
    }
}
