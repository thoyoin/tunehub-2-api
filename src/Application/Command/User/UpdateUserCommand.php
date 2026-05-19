<?php

declare(strict_types=1);

namespace App\Application\Command\User;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateUserCommand
{
    public function __construct(
        #[Assert\NotBlank]
        private ?string $id = null,

        #[Assert\Length(min:1, max: 50)]
        private ?string $username = null,

        #[Assert\Email(
            message: 'The email {{ value }} is not a valid email.',
        )]
        #[Assert\Length(max: 255)]
        private ?string $email = null,

        #[Assert\Image(
            maxSize: '5M',
            mimeTypes: [
                'image/jpeg',
                'image/png',
                'image/webp'
            ],
            mimeTypesMessage: 'Please upload a valid image file.',
        )]
        private ?UploadedFile $profilePicture = null,
    )
    {}

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getProfilePicture(): ?UploadedFile
    {
        return $this->profilePicture;
    }
}
