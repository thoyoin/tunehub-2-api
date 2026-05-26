<?php

declare(strict_types=1);

namespace App\Application\Command\Release;

use App\Domain\Entity\Release;

final readonly class PublishReleaseCommand
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
