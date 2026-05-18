<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Playlist;

use App\Application\Command\Playlist\CreatePlaylistCommand;
use App\Application\DTO\LibraryItem\LibraryItemDto;
use App\Application\Factory\LibraryItem\LibraryItemDtoFactory;
use App\Application\Factory\LibraryItem\LibraryItemFactory;
use App\Domain\Entity\Playlist;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class CreatePlaylistCommandHandler
{
    public function __construct(
        #[Autowire('%media.default_cover%')]
        private string $defaultCover,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private LibraryItemFactory $libraryItemFactory,
        private LibraryItemDtoFactory $libraryItemDtoFactory,
    )
    {}

    public function __invoke(CreatePlaylistCommand $command): LibraryItemDto
    {
        $playlist = new Playlist();

        $user = $this->userRepository->find($command->userId);

        $playlist->setOwner($user);
        $playlist->setTitle($command->title);
        $playlist->setCoverUrl($this->defaultCover);
        $playlist->setVisibility($command->visibility);

        $item = $this->libraryItemFactory->forPlaylist($user, $playlist);

        $this->entityManager->persist($item);
        $this->entityManager->persist($playlist);
        $this->entityManager->flush();

        return $this->libraryItemDtoFactory->create($item);
    }
}
