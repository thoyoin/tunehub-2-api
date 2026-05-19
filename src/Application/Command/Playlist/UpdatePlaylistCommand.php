<?php

declare(strict_types=1);

namespace App\Application\Command\Playlist;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePlaylistCommand
{
    public function __construct(
        #[Assert\NotBlank]
        public ?int $playlistId,

        #[Assert\Length(max:255)]
        public string $title = '',

        #[Assert\Length(max: 255)]
        public string $description = '',

        #[Assert\File(
            maxSize: '5M',
            mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
        )]
        public ?UploadedFile $cover = null,
    )
    {}
}
