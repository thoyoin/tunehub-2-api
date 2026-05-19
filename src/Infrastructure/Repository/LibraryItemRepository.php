<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\LibraryItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LibraryItem>
 */
class LibraryItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LibraryItem::class);
    }

    /**
     * @return array<int, LibraryItem>
     */
    public function getAllByUserId(int $userId): array
    {
        return $this->createQueryBuilder('l')
            ->where('l.user = :user')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getResult();
    }
}
