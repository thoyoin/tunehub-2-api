<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Release;
use App\Domain\Entity\User;
use App\Domain\Repository\ReleaseRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Release>
 */
class ReleaseRepository extends ServiceEntityRepository implements ReleaseRepositoryInterface
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

    public function getArtistLatestPublished(int $artistId): ?Release
    {
        /**
         * @var Release|null $result
         */
        $result = $this->createQueryBuilder('r')
            ->where('r.status = :status')
            ->setParameter('status', 'published')
            ->andWhere('r.artist = :artistId')
            ->setParameter('artistId', $artistId)
            ->orderBy('r.releaseDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }

    public function delete(Release $release): void
    {
        $this->getEntityManager()->remove($release);
        $this->getEntityManager()->flush();
    }

    public function getByArtist(User $artist): array
    {
        /**
         * @var array<int, Release> $result
         */
        $result = $this->createQueryBuilder('r')
            ->where('r.status = :status')
            ->setParameter('status', 'published')
            ->andWhere('r.artist = :artistId')
            ->setParameter('artistId', $artist)
            ->orderBy('r.releaseDate', 'DESC')
            ->getQuery()
            ->getResult();

        return $result;
    }
}
