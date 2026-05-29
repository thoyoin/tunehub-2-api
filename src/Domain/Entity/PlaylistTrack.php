<?php

namespace App\Domain\Entity;

use App\Repository\PlaylistTrackRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaylistTrackRepository::class)]
class PlaylistTrack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Playlist::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Playlist $playlist;

    #[ORM\ManyToOne(targetEntity: Track::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Track $track;

    #[ORM\Column]
    private \DateTimeImmutable $addedAt;

    public function __construct(Playlist $playlist, Track $track)
    {
        $this->playlist = $playlist;
        $this->track = $track;
        $this->addedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlaylist(): Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(Playlist $playlist): static
    {
        $this->playlist = $playlist;

        return $this;
    }

    public function getTrack(): Track
    {
        return $this->track;
    }

    public function setTrack(Track $track): static
    {
        $this->track = $track;

        return $this;
    }

    public function getAddedAt(): \DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeImmutable $addedAt): static
    {
        $this->addedAt = $addedAt;

        return $this;
    }
}
