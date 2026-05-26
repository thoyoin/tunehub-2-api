<?php

declare(strict_types=1);

namespace App\Application\Command\Release;

use App\Domain\ValueObject\ReleaseType;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UploadReleaseCommand
{
    /**
     * @param string[] $titles
     * @param UploadedFile[] $audioFiles
     */
    public function __construct(
        private string $releaseTitle,
        private ReleaseType $type,
        private \DateTimeImmutable $releaseDate,
        private int $artistId,
        private array $titles,
        private array $audioFiles,
        private UploadedFile $cover,
    )
    {}

    public function getReleaseTitle(): string
    {
        return $this->releaseTitle;
    }

    public function getType(): ReleaseType
    {
        return $this->type;
    }

    public function getReleaseDate(): DateTimeImmutable
    {
        return $this->releaseDate;
    }

    public function getArtistId(): int
    {
        return $this->artistId;
    }

    /**
     * @return string[]
     */
    public function getTitles(): array
    {
        return $this->titles;
    }

    /**
     * @return UploadedFile[]
     */
    public function getAudioFiles(): array
    {
        return $this->audioFiles;
    }

    public function getCover(): UploadedFile
    {
        return $this->cover;
    }
}
