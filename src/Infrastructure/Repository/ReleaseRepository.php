<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Release;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Release>
 */
class ReleaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Release::class);
    }

    /**
     * @return array<Release>
     */
    public function getLatestPublished(int $limit): array
    {
        /** @var Release[] $result */
        $result = $this->createQueryBuilder('r')
            ->where('r.status = :status')
            ->setParameter('status', 'published')
            ->orderBy('r.releaseDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $result;
    }
}
