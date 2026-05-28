<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Playlist;
use App\Domain\Entity\User;
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

    /**
     * @param User $user
     * @param array<int|string> $trackIds
     * @return array<int|string, array<int>>
     */
    public function findTrackPresenceForUser(User $user, array $trackIds): array
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

        /** @var array<array{track_id: int, playlist_id: int}> $results */
        foreach ($results as $row) {
            $stateMap[$row['track_id']][] = $row['playlist_id'];
        }

        return $stateMap;
    }
}
