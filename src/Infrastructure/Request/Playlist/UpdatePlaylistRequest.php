<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Playlist;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdatePlaylistRequest
{
    public function __construct(
        #[Assert\Length(max:255)]
        public ?string $title = null,

        #[Assert\Length(max: 255)]
        public ?string $description = null,
    )
    {}
}
