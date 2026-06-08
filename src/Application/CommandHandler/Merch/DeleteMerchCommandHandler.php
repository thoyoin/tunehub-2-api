<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Merch;

use App\Application\Command\Merch\DeleteMerchCommand;
use App\Infrastructure\Client\ShopwareClient;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteMerchCommandHandler
{
    public function __construct(
        private ShopwareClient $client,
    )
    {}

    public function __invoke(DeleteMerchCommand $command): void
    {
        $this->client->request('DELETE', 'product/' . $command->getId());
    }
}
