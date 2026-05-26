<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Playlist;

use App\Domain\ValueObject\PlaylistVisibility;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePlaylistVisibilityRequest
{
    public function __construct(

        #[Assert\NotBlank]
        #[Assert\Choice(choices: [PlaylistVisibility::Public->value, PlaylistVisibility::Private->value])]
        public string $visibility,
    )
    {}
}
