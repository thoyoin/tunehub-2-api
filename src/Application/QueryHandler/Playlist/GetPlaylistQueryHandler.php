<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Playlist;

use App\Application\DTO\Playlist\PlaylistDto;
use App\Application\Factory\Playlist\PlaylistDtoFactory;
use App\Application\Query\Playlist\GetPlaylistQuery;
use App\Domain\Entity\Playlist;

final readonly class GetPlaylistQueryHandler
{
    public function __construct(
        private PlaylistDtoFactory $playlistDtoFactory,
    )
    {}

    public function __invoke(GetPlaylistQuery $query): PlaylistDto
    {
        if (!$query->playlist instanceof Playlist) {
            throw new \DomainException('Playlist not found');
        }

        return $this->playlistDtoFactory->create($query->playlist);
    }
}
