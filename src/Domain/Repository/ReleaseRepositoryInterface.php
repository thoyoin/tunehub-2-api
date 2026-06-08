<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Release;
use App\Domain\Entity\User;

interface ReleaseRepositoryInterface
{
    /**
     * @return array<Release>
     */
    public function getLatestPublished(int $limit): array;

    public function getArtistLatestPublished(int $artistId): ?Release;

    public function delete(Release $release): void;

    /**
     * @return array<int, Release>
     */
    public function getByArtist(User $artist): array;
}
