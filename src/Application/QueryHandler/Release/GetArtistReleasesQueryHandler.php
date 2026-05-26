<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Release;

use App\Application\DTO\Release\ReleasePreviewDto;
use App\Application\Factory\Release\ReleaseDtoFactory;
use App\Application\Factory\Release\ReleasePreviewDtoFactory;
use App\Application\Query\Release\GetArtistReleasesQuery;
use App\Infrastructure\Repository\ReleaseRepository;

final readonly class GetArtistReleasesQueryHandler
{
    public function __construct(
        private ReleaseRepository $releaseRepository,
        private ReleasePreviewDtoFactory $dtoFactory,
    )
    {}

    /**
     * @return array<int, ReleasePreviewDto>
     */
    public function __invoke(GetArtistReleasesQuery $query): array
    {
        $releases = $this->releaseRepository->findBy(['artist' => $query->artist]);

        if (empty($releases)) {
            return [];
        }

        $dtos = [];

        foreach ($releases as $release) {
            $dtos[] = $this->dtoFactory->create($release);
        }

        return $dtos;
    }
}
