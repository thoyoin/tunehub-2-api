<?php

declare(strict_types=1);

namespace App\Application\Factory\Merch;

use App\Application\DTO\Merch\MerchVariantDto;
use App\Domain\Model\ArtistMerchVariant;

class MerchVariantDtoFactory
{
    /**
     * @param array<int, ArtistMerchVariant> $variants
     * @return array<int, MerchVariantDto>
     */
    public function create(array $variants): array
    {
        $dtos = [];

        foreach ($variants as $variant) {
            $dtos[] = new MerchVariantDto(
                id: $variant->id,
                title: $variant->title,
                price: $variant->price,
                stock: $variant->stock,
            );
        }

        return $dtos;
    }
}
