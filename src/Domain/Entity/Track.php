<?php

namespace App\Domain\Entity;

use App\Infrastructure\Repository\TrackRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
class Track
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'tracks')]
    #[ORM\JoinColumn(nullable: false)]
    private User $artist;

    #[ORM\ManyToOne(inversedBy: 'tracks')]
    #[ORM\JoinColumn(nullable: false)]
    private Release $release;

    #[ORM\ManyToOne(inversedBy: 'tracks')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Playlist $playlist = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $cover_url;

    #[ORM\Column]
    private int $duration;

    #[ORM\Column(length: 255)]
    private string $audio_url;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $release_date;

    #[ORM\Column]
    private int $position;

    public function getId(): int
    {
        return $this->id;
    }

    public function getArtist(): User
    {
        return $this->artist;
    }

    public function setArtist(User $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    public function getRelease(): Release
    {
        return $this->release;
    }

    public function setRelease(Release $release): static
    {
        $this->release = $release;

        return $this;
    }

    public function getPlaylist(): ?Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(?Playlist $playlist): static
    {
        $this->playlist = $playlist;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCoverUrl(): string
    {
        return $this->cover_url;
    }

    public function setCoverUrl(string $cover_url): static
    {
        $this->cover_url = $cover_url;

        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getAudioUrl(): string
    {
        return $this->audio_url;
    }

    public function setAudioUrl(string $audio_url): static
    {
        $this->audio_url = $audio_url;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeImmutable
    {
        return $this->release_date;
    }

    public function setReleaseDate(\DateTimeImmutable $release_date): static
    {
        $this->release_date = $release_date;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getFormattedDuration(): string
    {
        $minutes = (int) floor($this->duration / 60);

        $seconds = $this->duration % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
