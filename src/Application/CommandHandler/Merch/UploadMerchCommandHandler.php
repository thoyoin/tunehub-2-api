<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Merch;

use App\Application\Command\Merch\UploadMerchCommand;
use App\Infrastructure\Client\ShopwareClient;
use Lcobucci\JWT\Signer\Hmac\Sha256;

final readonly class UploadMerchCommandHandler
{
    public function __construct(
        private ShopwareClient $client,
        private string $defaultTaxId,
        private string $defaultEuroId,
    )
    {}

    public function __invoke(UploadMerchCommand $command): void
    {
        $baseSku = 'MERCH-' . strtoupper(substr(hash(Sha256::class, random_bytes(8)), 0, 8));

        $parentProductId = bin2hex(random_bytes(16));

        $this->client->request('POST', 'product', [
            'json' => [
                'id' => $parentProductId,
                'name' => $command->getItemTitle(),
                'productNumber' => $baseSku,
                'description' => $command->getItemDescription(),
                'taxId' => $this->defaultTaxId,
                'stock' => 0,
                'price' => [[
                    'currencyId' => $this->defaultEuroId,
                    'gross' => 0,
                    'net' => 0,
                    'linked' => true
                ]],
                'customFields' => [
                    'userId' => $command->getUserId(),
                ]
            ]
        ]);

        foreach ($command->getMerchVariants() as $index => $variantData) {
            $variantProductId = bin2hex(random_bytes(16));

            $this->client->request('POST', 'product', [
                'json' => [
                    'id' => $variantProductId,
                    'parentId' => $parentProductId,
                    'name' => $variantData->getVariantName(),
                    'productNumber' => $baseSku . '-' . ($index + 1),
                    'stock' => $variantData->getStock(),
                    'price' => [[
                        'currencyId' => $this->defaultEuroId,
                        'gross' => $variantData->getPrice(),
                        'net' => $variantData->getPrice() / 1.19,
                        'linked' => true
                    ]]
                ]
            ]);
        }
    }
}
