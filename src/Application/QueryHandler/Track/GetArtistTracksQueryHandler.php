<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Track;

use App\Application\DTO\Track\TrackDto;
use App\Application\Factory\Track\TrackDtoFactory;
use App\Application\Query\Track\GetArtistTracksQuery;
use App\Infrastructure\Repository\TrackRepository;

final readonly class GetArtistTracksQueryHandler
{
    public function __construct(
        private TrackRepository $trackRepository,
        private TrackDtoFactory $dtoFactory,
    )
    {}

    /**
     * @return array<int, TrackDto>
     */
    public function __invoke(GetArtistTracksQuery $query): array
    {
        $tracks = $this->trackRepository->findBy(['artist' => $query->artist]);

        if (empty($tracks)) {
            return [];
        }

        $dtos = [];

        foreach ($tracks as $track) {
            $dtos[] = $this->dtoFactory->create($track);
        }

        return $dtos;
    }
}
