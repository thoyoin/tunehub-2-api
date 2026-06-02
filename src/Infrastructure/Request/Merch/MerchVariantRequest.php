<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Merch;

use Symfony\Component\Validator\Constraints as Assert;

class MerchVariantRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 55)]
        public string $variantName,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'numeric')]
        #[Assert\GreaterThanOrEqual(value: 0.01)]
        public float|int $price,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        #[Assert\GreaterThanOrEqual(value: 0)]
        public int $stock,
    )
    {}
}
