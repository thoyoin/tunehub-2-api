<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function findTrackPresenceForUser($user, $trackIds): array
    {
        $results = $this->createQueryBuilder('p')
            ->select('IDENTITY(pt.track) as track_id', 'p.id as playlist_id')
            ->join('p.items', 'pt')
            ->where('p.owner = :user')
            ->andWhere('IDENTITY(pt.track) IN (:trackIds)')
            ->setParameter('user', $user)
            ->setParameter('trackIds', $trackIds)
            ->getQuery()
            ->getResult();

        $stateMap = [];

        foreach ($trackIds as $id) {
            $stateMap[$id] = [];
        }

        foreach ($results as $row) {
            $stateMap[$row['track_id']][] = (int)$row['playlist_id'];
        }

        return $stateMap;
    }
}
