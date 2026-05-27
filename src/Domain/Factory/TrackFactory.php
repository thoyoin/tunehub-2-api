<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Release;
use App\Domain\Entity\Track;
use App\Domain\Entity\User;
use DateTimeImmutable;

class TrackFactory
{
    public function create(
        string $title,
        string $audioUrl,
        int $duration,
        User $artist,
        Release $release,
        int $position,
        string $coverUrl,
        DateTimeImmutable $releaseDate,
    ): Track
    {
        $track = new Track();

        $track->setTitle($title);
        $track->setAudioUrl($audioUrl);
        $track->setDuration($duration);
        $track->setArtist($artist);
        $track->setRelease($release);
        $track->setPosition($position);
        $track->setCoverUrl($coverUrl);
        $track->setReleaseDate($releaseDate);

        return $track;
    }
}
