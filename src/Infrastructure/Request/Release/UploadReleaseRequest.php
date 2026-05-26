<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Release;

use App\Domain\ValueObject\ReleaseType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UploadReleaseRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 100)]
        public string $releaseTitle,

        #[Assert\NotBlank]
        public ReleaseType $type,

        #[Assert\NotBlank]
        public \DateTimeImmutable $releaseDate,

        #[Assert\NotBlank]
        public int $artistId,

        /**
         * @var array<int, string>
         */
        #[Assert\Count(min: 1)]
        #[Assert\All([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length(min: 1, max: 100),
        ])]
        public array $titles,

        /**
         * @var array<int, UploadedFile>
         */
        #[Assert\Count(min: 1)]
        #[Assert\All([
            new Assert\File(
                maxSize: '50M',
                mimeTypes: [
                    'audio/mpeg',
                    'audio/mp3',
                    'audio/wav',
                    'audio/x-wav',
                    'audio/flac',
                    'audio/x-flac',
                    'audio/aac',
                    'audio/ogg',
                ],
                mimeTypesMessage: 'Please upload a valid audio file.',
            ),
        ])]
        public array $audioFiles,

        #[Assert\Image(
            maxSize: '5M',
            mimeTypes: [
                'image/jpeg',
                'image/png',
                'image/webp',
            ],
            mimeTypesMessage: 'Please upload a valid cover image.',
        )]
        public UploadedFile $cover,
    )
    {}
}
