<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\LibraryItem;

use App\Application\DTO\LibraryItem\LibraryItemDto;
use App\Application\Factory\LibraryItem\LibraryItemDtoFactory;
use App\Application\Query\LibraryItem\GetLibraryItemByIdQuery;
use App\Domain\Entity\LibraryItem;
use App\Infrastructure\Repository\LibraryItemRepository;

readonly class GetLibraryItemByIdQueryHandler
{
    public function __construct(
        private LibraryItemRepository $repository,
        private LibraryItemDtoFactory $dtoFactory
    )
    {}

    public function __invoke(GetLibraryItemByIdQuery $query): LibraryItemDto
    {
        $libItem = $this->repository->find($query->itemId);

        if (!$libItem instanceof LibraryItem) {
            throw new \DomainException('Library item not found');
        }

        return $this->dtoFactory->create($libItem);
    }
}
