<?php

declare (strict_types=1);

namespace App\Application\DTO\Merch;

final readonly class MerchVariantDto
{
    public function __construct(
        private ?string $id,
        private ?string $title,
        private ?float $price,
        private ?int $stock
    )
    {}

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }
}
