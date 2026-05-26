<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Playlist;

use App\Application\DTO\Playlist\PlaylistDto;
use App\Application\Factory\Playlist\PlaylistDtoFactory;
use App\Application\Query\Playlist\GetPlaylistQuery;

final readonly class GetPlaylistQueryHandler
{
    public function __construct(
        private PlaylistDtoFactory $playlistDtoFactory,
    )
    {}

    public function __invoke(GetPlaylistQuery $query): PlaylistDto
    {
        return $this->playlistDtoFactory->create($query->playlist);
    }
}
