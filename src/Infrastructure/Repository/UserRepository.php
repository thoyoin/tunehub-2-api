<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function existsByEmail(string $email): bool
    {
        return $this->createQueryBuilder('u')
            ->select('1')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult() !== null;
    }

    public function existsByUsername(string $username): bool
    {
        return $this->createQueryBuilder('u')
                ->select('1')
                ->where('u.slug = :username')
                ->setParameter('username', $username)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult() !== null;
    }

    public function getOneById(int $id): User
    {
        $user = $this->find($id);

        if ($user === null) {
            throw new \DomainException('User not found.');
        }

        return $user;
    }
}
