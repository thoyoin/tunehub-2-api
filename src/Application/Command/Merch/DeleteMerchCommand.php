<?php

declare(strict_types=1);

namespace App\Application\Command\Merch;

final readonly class DeleteMerchCommand
{
    public function __construct(
        private string $id,
    )
    {}

    public function getId(): string
    {
        return $this->id;
    }
}
