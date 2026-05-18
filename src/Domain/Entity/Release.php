<?php

namespace App\Domain\Entity;

use App\Infrastructure\Repository\ReleaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'releases')]
#[ORM\Entity(repositoryClass: ReleaseRepository::class)]
class Release
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'releases')]
    #[ORM\JoinColumn(nullable: false)]
    private User $artist;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $releaseType;

    #[ORM\Column(length: 255)]
    private string $coverUrl;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $releaseDate;

    #[ORM\Column(length: 255, options: ['default' => 'pending'])]
    private string $status = 'pending';

    /**
     * @var Collection<int, Track>
     */
    #[ORM\OneToMany(targetEntity: Track::class, mappedBy: 'release')]
    private Collection $tracks;

    #[ORM\Column(nullable: true)]
    private ?int $itemId = null;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseType(): ?string
    {
        return $this->releaseType;
    }

    public function setReleaseType(string $releaseType): static
    {
        $this->releaseType = $releaseType;

        return $this;
    }

    public function getCoverUrl(): ?string
    {
        return $this->coverUrl;
    }

    public function setCoverUrl(string $coverUrl): static
    {
        $this->coverUrl= $coverUrl;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeImmutable
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeImmutable $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Track>
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): static
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks->add($track);
            $track->setRelease($this);
        }

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'artist' => $this->getArtist(),
            'title' => $this->getTitle(),
            'releaseType' => $this->getReleaseType(),
            'coverUrl' => $this->getCoverUrl(),
            'releaseDate' => $this->getReleaseDate(),
            'status' => $this->getStatus(),
            'tracks' => $this->getTracks()->toArray(),
        ];
    }

    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function setItemId(int $itemId): static
    {
        $this->itemId = $itemId;

        return $this;
    }
}
