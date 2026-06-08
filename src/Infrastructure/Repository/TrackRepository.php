<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Track;
use App\Domain\Repository\TrackRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Track>
 */
class TrackRepository extends ServiceEntityRepository implements TrackRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Track::class);
    }

    public function getArtistTop(int $artistId): array
    {
        /**
         * @var array<int, Track> $result
         */
        $result = $this->createQueryBuilder('t')
            ->where('t.artist = :artistId')
            ->setParameter('artistId', $artistId)
            ->orderBy('t.releaseDate', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $result;
    }
}
