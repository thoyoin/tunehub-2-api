<?php

declare(strict_types=1);

namespace App\Application\Factory\Merch;

use App\Application\DTO\Merch\MerchDto;
use App\Domain\Model\ArtistMerch;

final readonly class MerchDtoFactory
{
    public function __construct(
        private MerchVariantDtoFactory $variantDtoFactory,
    )
    {}

    /**
     * @param array<ArtistMerch> $artistMerch
     * @return array<int, MerchDto>
     */
    public function create(array $artistMerch): array
    {
        $dtos = [];

        foreach($artistMerch as $item) {
            $dtos[] = new MerchDto(
                id: $item->id,
                isActive: $item->isActive,
                title: $item->title,
                variants: $this->variantDtoFactory->create($item->variants),
                userId: $item->userId,
                cover: $item->cover,
                description: $item->description,
            );
        }

        return $dtos;
    }
}
