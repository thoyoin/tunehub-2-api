<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

enum LibraryItemType: string
{
    case Playlist = 'playlist';
    case Release = 'release';
}
