<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

enum ReleaseType: string
{
    case Album = 'album';
    case Single = 'single';
}
