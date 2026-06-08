<?php

declare(strict_types=1);

namespace App\Domain\Model;

final readonly class ArtistMerch
{
    /**
     * @param array<int, ArtistMerchVariant> $variants
     */
    public function __construct(
        public string $id,
        public string $title,
        public bool $isActive,
        public string $cover,
        public string $userId,
        public ?string $description = null,
        public array $variants = [],
    )
    {}
}
