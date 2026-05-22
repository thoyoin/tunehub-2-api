<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Release;

use App\Application\DTO\Release\ReleasePreviewDto;
use App\Application\Factory\Release\ReleasePreviewDtoFactory;
use App\Application\Query\Release\GetLatestReleasesQuery;
use App\Infrastructure\Repository\ReleaseRepository;

readonly class GetLatestReleasesQueryHandler
{
    public function __construct(
        private ReleaseRepository $releaseRepository,
        private ReleasePreviewDtoFactory $dtoFactory,
    )
    {}

    /**
     * @return array<int, ReleasePreviewDto>
     */
    public function __invoke(GetLatestReleasesQuery $query): array
    {
        $releases = $this->releaseRepository->getLatestPublished($query->limit);

        $dtos = [];

        foreach ($releases as $release) {
            $dtos[] = $this->dtoFactory->create($release);
        }

        return $dtos;
    }
}
