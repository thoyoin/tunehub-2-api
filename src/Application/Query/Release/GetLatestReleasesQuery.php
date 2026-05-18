<?php

declare(strict_types=1);

namespace App\Application\Query\Release;

class GetLatestReleasesQuery
{
    public function __construct(
        public int $limit = 10
    )
    {}
}
