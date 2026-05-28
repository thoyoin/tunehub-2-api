<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Playlist;

use App\Application\DTO\Playlist\PlaylistPreviewDto;
use App\Application\Factory\Playlist\PlaylistDtoFactory;
use App\Application\Query\Playlist\GetAllPlaylistsQuery;

final readonly class GetAllPlaylistsQueryHandler
{
    public function __construct(
        private PlaylistDtoFactory $playlistDtoFactory,
    )
    {}

    /**
     * @return array<int, PlaylistPreviewDto>
     */
    public function __invoke(GetAllPlaylistsQuery $query): array
    {
        $playlists = $query->getUser()
            ->getPlaylists();

        $playlistDtos = [];

        foreach ($playlists as $playlist) {
            $playlistDtos[] = $this->playlistDtoFactory->createPreview($playlist);
        }

        return $playlistDtos;
    }
}
