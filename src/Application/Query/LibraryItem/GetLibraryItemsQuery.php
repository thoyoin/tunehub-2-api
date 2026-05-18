<?php

declare(strict_types=1);

namespace App\Application\Query\LibraryItem;

final readonly class GetLibraryItemsQuery
{
    public function __construct(
        public int $userId,
    )
    {}
}
