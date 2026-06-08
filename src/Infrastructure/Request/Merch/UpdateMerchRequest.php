<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Merch;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateMerchRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $id,

        #[Assert\Length(max: 55)]
        public ?string $itemTitle = null,

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

        /** @var UpdateMerchVariantRequest[] */
        public ?array $merchVariants = null,
    )
    {}
}
