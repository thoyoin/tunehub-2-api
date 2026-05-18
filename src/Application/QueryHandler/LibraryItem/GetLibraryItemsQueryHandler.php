<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\LibraryItem;

use App\Application\Factory\LibraryItem\LibraryItemDtoFactory;
use App\Application\Query\LibraryItem\GetLibraryItemsQuery;
use App\Domain\Entity\LibraryItem;
use App\Infrastructure\Repository\LibraryItemRepository;

final readonly class GetLibraryItemsQueryHandler
{
    public function __construct(
        private LibraryItemRepository $repository,
        private LibraryItemDtoFactory $dtoFactory,
    )
    {}

    public function __invoke(GetLibraryItemsQuery $query): array
    {
        $libItems = $this->repository->getAllByUserId($query->userId);

        return array_map(
            fn (LibraryItem $item) => $this->dtoFactory->create($item),
            $libItems
        );
    }
}
