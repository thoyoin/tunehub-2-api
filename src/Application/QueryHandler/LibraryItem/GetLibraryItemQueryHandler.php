<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\LibraryItem;

use App\Application\DTO\LibraryItem\LibraryItemDto;
use App\Application\Factory\LibraryItem\LibraryItemDtoFactory;
use App\Application\Query\LibraryItem\GetLibraryItemQuery;

readonly class GetLibraryItemQueryHandler
{
    public function __construct(
        private LibraryItemDtoFactory $dtoFactory
    )
    {}

    public function __invoke(GetLibraryItemQuery $query): LibraryItemDto
    {
        return $this->dtoFactory->create($query->item);
    }
}
