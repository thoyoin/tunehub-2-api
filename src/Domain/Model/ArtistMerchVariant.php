<?php

declare(strict_types=1);

namespace App\Domain\Model;

class ArtistMerchVariant
{
    public function __construct(
        public string $id,
        public string $title,
        public float $price,
        public int $stock,
    )
    {}
}
