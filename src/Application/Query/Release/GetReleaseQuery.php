<?php

declare(strict_types=1);

namespace App\Application\Query\Release;

use App\Domain\Entity\Release;

final readonly class GetReleaseQuery
{
    public function __construct(
        private Release $release
    )
    {}

    public function getRelease(): Release
    {
        return $this->release;
    }
}
