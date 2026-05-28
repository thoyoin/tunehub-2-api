<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Release;

use App\Application\DTO\Release\ReleaseDto;
use App\Application\Factory\Release\ReleaseDtoFactory;
use App\Application\Query\Release\GetReleaseQuery;

final readonly class GetReleaseQueryHandler
{
    public function __construct(
        private ReleaseDtoFactory $dtoFactory,
    )
    {}

    public function __invoke(GetReleaseQuery $query): ReleaseDto
    {
        return $this->dtoFactory->create($query->getRelease());
    }
}
