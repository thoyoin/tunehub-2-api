<?php

declare (strict_types=1);

namespace App\Application\DTO\Merch;

final readonly class MerchVariantDto
{
    public function __construct(
        private string $variantName,
        private float $price,
        private int $stock
    )
    {}

    public function getVariantName() : string
    {
        return $this->variantName;
    }

    public function getPrice() : float
    {
        return $this->price;
    }

    public function getStock() : int
    {
        return $this->stock;
    }
}
