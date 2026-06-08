<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Merch;

use App\Application\Command\Merch\UpdateMerchCommand;
use App\Infrastructure\Client\ShopwareClient;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateMerchCommandHandler
{
    public function __construct(
        private ShopwareClient $client,
        private string $defaultEuroId,
    )
    {}

    public function __invoke(UpdateMerchCommand $command): void
    {
        $childrenData = [];

        $variants = $command->getVariants();

        if ($variants !== null) {
            foreach($variants as $variant) {
                $variantPayload = [
                    'id' => $variant->getId(),
                    'stock' => $variant->getStock(),
                ];

                if ($variant->getTitle() !== '') {
                    $variantPayload['name'] = $variant->getTitle();
                }

                if ($variant->getPrice() < 0.0) {
                    $variantPayload['price'] = [[
                        'currencyId' => $this->defaultEuroId,
                        'gross' => $variant->getPrice(),
                        'net' => $variant->getPrice() / 1.19,
                        'linked' => true
                    ]];
                }

                $childrenData[] = $variantPayload;
            }
        }

        $endpoint = sprintf('product/%s', $command->getId());

        try {
            $this->client->request('PATCH', $endpoint, [
                'json' => [
                    'id' => $command->getId(),
                    'name' => $command->getTitle(),
                    'description' => $command->getDescription(),
                    'children' => $childrenData
                ]
            ]);
        } catch (ClientException $e) {
            $shopwareErrorMessage = $e->getResponse()->getContent(false);

            throw new \RuntimeException(
                sprintf('Shopware Validation Error: %s', $shopwareErrorMessage),
                $e->getCode(),
                $e
            );
        }
    }
}
