<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Merch;

use App\Application\DTO\Merch\MerchDto;
use App\Application\Factory\Merch\MerchDtoFactory;
use App\Application\Query\Merch\GetArtistMerchQuery;
use App\Infrastructure\Client\ShopwareClient;
use App\Infrastructure\Mapper\ShopwareMerchMapper;

final readonly class GetArtistMerchQueryHandler
{
    public function __construct(
        private ShopwareClient $shopwareClient,
        private MerchDtoFactory $dtoFactory,
        private ShopwareMerchMapper $mapper,
    )
    {}

    /**
     * @return array<int, MerchDto>
     */
    public function __invoke(GetArtistMerchQuery $query): array
    {
        $result = $this->shopwareClient->request('POST', 'search/product', [
            'json' => [
                'filter' => [
                    [
                        'type' => 'equals',
                        'field' => 'customFields.userId',
                        'value' => $query->getUserId(),
                    ],
                    [
                        'type' => 'equals',
                        'field' => 'parentId',
                        'value' => null,
                    ]
                ],
                'associations' => [
                    'children' => []
                ]
            ]
        ]);

        /**
         * @var array<int, array<string, mixed>> $data
         */
        $data = $result['data'] ?? [];

        $mappedData = $this->mapper->mapCollection($data);

        return $this->dtoFactory->create($mappedData);
    }
}
