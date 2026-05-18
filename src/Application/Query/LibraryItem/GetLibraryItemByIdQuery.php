<?php

declare(strict_types=1);

namespace App\Application\Query\LibraryItem;

final readonly class GetLibraryItemByIdQuery
{
    public function __construct(
        public int $itemId,
    )
    {}
}
