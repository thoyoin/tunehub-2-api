<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\User;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateUserRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $userId = null,

        #[Assert\Length(min:1, max: 50)]
        public ?string $username = null,

        #[Assert\Email(
            message: 'The email {{ value }} is not a valid email.',
        )]
        #[Assert\Length(max: 255)]
        public ?string $email = null,

        #[Assert\Image(
            maxSize: '5M',
            mimeTypes: [
                'image/jpeg',
                'image/png',
                'image/webp'
            ],
            mimeTypesMessage: 'Please upload a valid image file.',
        )]
        public ?UploadedFile $profilePicture = null,
    )
    {}
}
