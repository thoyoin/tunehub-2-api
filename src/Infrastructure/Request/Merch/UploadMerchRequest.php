<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Merch;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UploadMerchRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'Title is required')]
        #[Assert\Length(max: 55)]
        public string $itemTitle,

        #[Assert\Length(max: 255)]
        public ?string $itemDescription = null,

        /** @var UploadedFile[]|null */
        #[Assert\All(
            constraints: [
                new Assert\Image(
                    maxSize: '5M',
                    mimeTypesMessage: 'File has to be image (jpg, jpeg, png, gif)'
                )
            ]
        )]
        public ?array $images = null,

        /** @var MerchVariantRequest[] */
        #[Assert\NotBlank(message: 'Merch variants are required')]
        #[Assert\Count(min: 1, minMessage: 'At least one variant can be uploaded')]
        #[Assert\Valid]
        public array $merchVariants = [],
    )
    {}
}
