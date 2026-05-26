<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

enum ReleaseStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case PUBLISHED = 'published';
}
