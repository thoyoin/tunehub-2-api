<?php

declare(strict_types=1);

namespace App\Application\Command\Release;

use App\Domain\Entity\Release;
use App\Domain\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UpdateReleaseCommand
{
    public function __construct(
        private Release $release,
        private User $artist,
        private ?string $releaseTitle,
        private ?UploadedFile $cover,
    )
    {}

    public function getReleaseTitle(): ?string
    {
        return $this->releaseTitle;
    }

    public function getArtist(): User
    {
        return $this->artist;
    }

    public function getRelease(): Release
    {
        return $this->release;
    }

    public function getCover(): ?UploadedFile
    {
        return $this->cover;
    }
}
