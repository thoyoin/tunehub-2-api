<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Merch;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateMerchVariantRequest
{
    public function __construct(
        #[Assert\Length(max: 55)]
        public ?string $variantName,

        #[Assert\Type(type: 'numeric')]
        #[Assert\GreaterThanOrEqual(value: 0.01)]
        public ?int $price,

        #[Assert\Type(type: 'integer')]
        #[Assert\GreaterThanOrEqual(value: 0)]
        public ?int $stock,

        public ?string $id,
    )
    {}
}
