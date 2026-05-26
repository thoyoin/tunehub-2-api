<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Release;

use App\Domain\ValueObject\ReleaseType;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UploadReleaseRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 100)]
        public string $releaseTitle,

        #[Assert\NotBlank]
        public ReleaseType $type,

        #[Assert\NotBlank]
        public \DateTimeImmutable $releaseDate,

        #[Assert\NotBlank]
        public int $artistId,

        /**
         * @var array<int, string>
         */
        public array $titles,
    )
    {}
}
