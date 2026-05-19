<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Playlist;

use App\Application\DTO\Playlist\PlaylistDto;
use App\Application\Factory\Playlist\PlaylistDtoFactory;
use App\Application\Query\Playlist\GetPlaylistByIdQuery;
use App\Domain\Entity\Playlist;
use App\Infrastructure\Repository\PlaylistRepository;

final readonly class GetPlaylistByIdQueryHandler
{
    public function __construct(
        private PlaylistRepository $playlistRepository,
        private PlaylistDtoFactory $playlistDtoFactory,
    )
    {}

    public function __invoke(GetPlaylistByIdQuery $query): PlaylistDto
    {
        $playlist = $this->playlistRepository->find($query->playlistId);

        if (!$playlist instanceof Playlist) {
            throw new \DomainException('Playlist not found');
        }

        return $this->playlistDtoFactory->create($playlist);
    }
}
