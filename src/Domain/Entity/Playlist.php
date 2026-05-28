<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\PlaylistVisibility;
use App\Infrastructure\Repository\PlaylistRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Playlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private string $itemType = 'playlist';

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'playlists')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $owner;

    #[ORM\Column(enumType: PlaylistVisibility::class)]
    private PlaylistVisibility $visibility = PlaylistVisibility::Private;

    #[ORM\Column(length: 255)]
    private string $cover_url;

    /**
     * @var Collection<int, PlaylistTrack>
     */
    #[ORM\OneToMany(
        targetEntity: PlaylistTrack::class,
        mappedBy: 'playlist',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $items;

    #[ORM\Column(nullable: false)]
    private DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->items = new ArrayCollection();

        $this->createdAt = new DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function generateSlug(): void
    {
        if ($this->slug === null || $this->slug === '') {
            $slugger = new AsciiSlugger();

            $baseSlug = strtolower($slugger->slug($this->title)->toString());

            $this->slug = sprintf(
                '%s-%s',
                $baseSlug,
                substr(Uuid::uuid4()->toString(), 0, 8),
            );
        }
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): string
    {
        if ($this->slug === null) {
            throw new \LogicException('Playlist slug has not been generated yet.');
        }

        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getVisibility(): PlaylistVisibility
    {
        return $this->visibility;
    }

    public function setVisibility(PlaylistVisibility $visibility): static
    {
        $this->visibility = $visibility;

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

    /**
     * @return Collection<int, PlaylistTrack>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function hasTrack(Track $track): bool
    {
        foreach ($this->items as $item) {
            if ($item->getTrack() === $track) {
                return true;
            }
        }

        return false;
    }

    public function addTrack(Track $track): static
    {
        if (!$this->hasTrack($track)) {
            $this->items->add(new PlaylistTrack($this, $track));
        }

        return $this;
    }

    public function removeTrack(Track $track): static
    {
        foreach ($this->items as $item) {
            if ($item->getTrack() === $track) {
                $this->items->removeElement($item);
                break;
            }
        }

        return $this;
    }

    public function getItemType(): string
    {
        return $this->itemType;
    }

    public function setItemType(string $itemType): static
    {
        $this->itemType = $itemType;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getDuration(): string
    {
        $nonformatted = 0;

        foreach ($this->items as $item) {
            $track = $item->getTrack();

            $nonformatted += $track->getDuration();
        }

        $minutes = (int) floor($nonformatted / 60);
        $seconds = $nonformatted % 60;

        return sprintf('%2d min %02d sec', $minutes, $seconds);
    }
}
