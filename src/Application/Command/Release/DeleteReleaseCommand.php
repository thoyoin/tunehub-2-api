<?php

declare(strict_types=1);

namespace App\Application\Command\Release;

use App\Domain\Entity\Release;

final readonly class DeleteReleaseCommand implements \App\Application\Command\CommandInterface
{
    public function __construct(
        private Release $release,
    )
    {}

    public function getRelease(): Release
    {
        return $this->release;
    }
}
