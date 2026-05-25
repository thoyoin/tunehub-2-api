<?php

namespace App\Domain\Entity;

use App\Infrastructure\Repository\UserRepository;
use App\Domain\ValueObject\UserRole;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255, unique: true)]
    private string $username;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $email;

    #[ORM\Column(length: 255)]
    private string $password;

    #[ORM\Column(length: 255)]
    private string $profilePicture;

    #[ORM\Column(enumType: UserRole::class)]
    private UserRole $role = UserRole::User;

    /**
     * @var Collection<int, Track>
     */
    #[ORM\OneToMany(targetEntity: Track::class, mappedBy: 'artist')]
    private Collection $tracks;

    /**
     * @var Collection<int, Release>
     */
    #[ORM\OneToMany(targetEntity: Release::class, mappedBy: 'artist')]
    private Collection $releases;

    /**
     * @var Collection<int, Playlist>
     */
    #[ORM\OneToMany(targetEntity: Playlist::class, mappedBy: 'owner')]
    private Collection $playlists;

    /**
     * @var Collection<int, LibraryItem>
     */
    #[ORM\OneToMany(targetEntity: LibraryItem::class, mappedBy: 'user')]
    private Collection $libraryItems;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
        $this->releases = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->libraryItems = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function generateSlug(): void
    {
        if ($this->slug === null || $this->slug === '') {
            $slugger = new AsciiSlugger();
            $this->slug = strtolower($slugger->slug($this->username)->toString());
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getProfilePicture(): string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(string $profilePicture): static
    {
        $this->profilePicture= $profilePicture;

        return $this;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): static
    {
        $this->role = $role;

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
            $track->setArtist($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Release>
     */
    public function getReleases(): Collection
    {
        return $this->releases;
    }

    public function addRelease(Release $release): static
    {
        if (!$this->releases->contains($release)) {
            $this->releases->add($release);
            $release->setArtist($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): static
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
            $playlist->setOwner($this);
        }

        return $this;
    }

    public function getRoles(): array
    {
        return [$this->role->value];
    }

    public function getUserIdentifier(): string
    {
        if ($this->email === '') {
            throw new \LogicException('User email must not be empty.');
        }

        return $this->email;
    }

    /**
     * @return Collection<int, LibraryItem>
     */
    public function getLibraryItems(): Collection
    {
        return $this->libraryItems;
    }

    public function addLibraryItem(LibraryItem $libraryItem): static
    {
        if (!$this->libraryItems->contains($libraryItem)) {
            $this->libraryItems->add($libraryItem);
            $libraryItem->setUser($this);
        }

        return $this;
    }
}
