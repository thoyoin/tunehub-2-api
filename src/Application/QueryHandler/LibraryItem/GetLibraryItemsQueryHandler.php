<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\LibraryItem;

use App\Application\DTO\LibraryItem\LibraryItemDto;
use App\Application\Factory\LibraryItem\LibraryItemDtoFactory;
use App\Application\Query\LibraryItem\GetLibraryItemsQuery;
use App\Domain\Repository\LibraryItemRepositoryInterface;
use App\Infrastructure\Repository\LibraryItemRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetLibraryItemsQueryHandler
{
    public function __construct(
        private LibraryItemRepositoryInterface $repository,
        private LibraryItemDtoFactory $dtoFactory,
    )
    {}

    /**
     * @return array<int, LibraryItemDto>
     */
    public function __invoke(GetLibraryItemsQuery $query): array
    {
        $libItems = $this->repository->getAllByUserId($query->userId);

        $dtos = [];

        foreach ($libItems as $libItem) {
            $dtos[] = $this->dtoFactory->create($libItem);
        }

        return $dtos;
    }
}
