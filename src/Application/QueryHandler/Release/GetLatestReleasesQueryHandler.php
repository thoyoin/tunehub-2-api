<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Release;

use App\Application\Query\Release\GetLatestReleasesQuery;
use App\Infrastructure\Repository\ReleaseRepository;

readonly class GetLatestReleasesQueryHandler
{
    public function __construct(
        private ReleaseRepository $releaseRepository,
    )
    {}

    public function __invoke(GetLatestReleasesQuery $query): array
    {
        return $this->releaseRepository->getLatestPublished($query->limit);
    }
}
