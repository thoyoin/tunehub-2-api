<?php

declare(strict_types=1);

namespace App\Application\Factory\LibraryItem;

use App\Domain\Entity\LibraryItem;
use App\Domain\Entity\Playlist;
use App\Domain\Entity\Release;
use App\Domain\Entity\User;

class LibraryItemFactory
{
    public function forPlaylist(User $user, Playlist $playlist): LibraryItem
    {
        $item = new LibraryItem();

        $item->setUser($user);
        $item->setPlaylist($playlist);

        return $item;
    }

    public function forRelease(User $user, Release $release): LibraryItem
    {
        $item = new LibraryItem();

        $item->setUser($user);
        $item->setRelease($release);

        return $item;
    }
}
