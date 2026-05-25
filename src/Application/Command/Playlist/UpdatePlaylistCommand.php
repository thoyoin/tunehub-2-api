<?php

declare(strict_types=1);

namespace App\Application\Command\Playlist;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UpdatePlaylistCommand
{
    public function __construct(
        private ?int $playlistId,
        private ?string $title = null,
        private ?string $description = null,
        private ?UploadedFile $cover = null,
    )
    {}

    public function getPlaylistId(): ?int
    {
        return $this->playlistId;
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
