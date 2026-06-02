<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Merch;

use App\Application\Query\Merch\GetArtistMerchQuery;
use App\Infrastructure\Client\ShopwareClient;

final readonly class GetArtistMerchQueryHandler
{
    public function __construct(
        private ShopwareClient $shopwareClient,
    )
    {}

    /**
     * @return array<int, array<string, mixed>>
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

        return $data;
    }
}
