<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper;

use App\Domain\Model\ArtistMerch;
use App\Domain\Model\ArtistMerchVariant;

class ShopwareMerchMapper
{
    /**
     * @param array<mixed> $item
     * @return ArtistMerch
     */
    public function map(array $item): ArtistMerch
    {
        $children = [];

        if (isset($item['children']) && is_array($item['children'])) {
            foreach ($item['children'] as $child) {
                if (!is_array($child)) {
                    continue;
                }

                $rawId = $child['id'] ?? null;
                $rawTitle = $child['name'] ?? null;
                $rawStock = $child['availableStock'] ?? null;
                $rawPriceArray = $child['price'] ?? null;

                $grossPrice = 0.0;
                if (is_array($rawPriceArray) && isset($rawPriceArray[0]) && is_array($rawPriceArray[0])) {
                    $rawGross = $rawPriceArray[0]['gross'] ?? null;
                    if (is_float($rawGross)) {
                        $grossPrice = $rawGross;
                    } elseif (is_int($rawGross)) {
                        $grossPrice = (float) $rawGross;
                    }
                }

                $children[] = new ArtistMerchVariant(
                    id: is_string($rawId) ? $rawId : '',
                    title: is_string($rawTitle) ? $rawTitle : '',
                    price: $grossPrice,
                    stock: is_int($rawStock) ? $rawStock : 0,
                );
            }
        }

        $itemId = $item['id'] ?? null;
        $itemTitle = $item['name'] ?? null;
        $itemActive = $item['active'] ?? null;
        $itemCover = $item['cover'] ?? null;
        $description = $item['description'] ?? null;
        $customFields = $item['customFields'] ?? null;
        $userId = '';

        if (is_array($customFields) && isset($customFields['userId']) && is_string($customFields['userId'])) {
            $userId = $customFields['userId'];
        }

        return new ArtistMerch(
            id: is_string($itemId) ? $itemId : '',
            title: is_string($itemTitle) ? $itemTitle : '',
            isActive: is_bool($itemActive) ? $itemActive : false,
            cover: is_string($itemCover) ? $itemCover : '',
            userId: $userId,
            description: is_string($description) ? $description : null,
            variants: $children,
        );
    }

    /**
     * @param array<mixed> $collection
     * @return array<ArtistMerch>
     */
    public function mapCollection(array $collection): array
    {
        return array_map(
            fn(mixed $item) => $this->map((array)$item),
            $collection
        );
    }
}
