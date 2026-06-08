<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\LibraryItem;

interface LibraryItemRepositoryInterface
{
    /**
     * @return array<int, LibraryItem>
     */
    public function getAllByUserId(int $userId): array;
}
