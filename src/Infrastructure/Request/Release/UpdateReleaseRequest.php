<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Release;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateReleaseRequest
{
    public function __construct(
        #[Assert\Length(max: 255)]
        public ?string $releaseTitle = null,
    )
    {}
}
