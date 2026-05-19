<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Playlist;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdatePlaylistRequest
{
    public function __construct(
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
