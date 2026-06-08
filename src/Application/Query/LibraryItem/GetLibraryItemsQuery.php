<?php

declare(strict_types=1);

namespace App\Application\Query\LibraryItem;

final readonly class GetLibraryItemsQuery implements \App\Application\Query\QueryInterface
{
    public function __construct(
        public int $userId,
    )
    {}
}
