<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Artist;

use App\Application\DTO\Track\TrackDto;
use App\Application\Factory\Track\TrackDtoFactory;
use App\Application\Query\Artist\GetArtistTopTracksQuery;
use App\Domain\Repository\TrackRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetArtistTopTracksQueryHandler
{
    public function __construct(
        private TrackRepositoryInterface $trackRepository,
        private TrackDtoFactory $dtoFactory,
    )
    {}

    /**
     * @return array<int, TrackDto>
     */
    public function __invoke(GetArtistTopTracksQuery $query): array
    {
        $tracks = $this->trackRepository->getArtistTop($query->getArtist()->getId());

        $dtos = [];

        foreach ($tracks as $track) {
            $dtos[] = $this->dtoFactory->create($track);
        }

        return $dtos;
    }
}
