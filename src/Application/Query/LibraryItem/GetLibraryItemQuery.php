<?php

declare(strict_types=1);

namespace App\Application\Query\LibraryItem;

use App\Domain\Entity\LibraryItem;

final readonly class GetLibraryItemQuery implements \App\Application\Query\QueryInterface
{
    public function __construct(
        public LibraryItem $item,
    )
    {}
}
