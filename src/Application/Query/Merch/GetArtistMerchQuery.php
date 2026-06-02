<?php

declare(strict_types=1);

namespace App\Application\Query\Merch;


final readonly class GetArtistMerchQuery
{
    public function __construct(
        private int $userId,
    )
    {}

    public function getUserId(): int
    {
        return $this->userId;
    }
}
