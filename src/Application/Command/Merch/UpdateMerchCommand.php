<?php

declare(strict_types=1);

namespace App\Application\Command\Merch;

use App\Application\DTO\Merch\MerchVariantDto;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UpdateMerchCommand implements \App\Application\Command\CommandInterface
{
    /**
     * @param MerchVariantDto[] $variants
     * @param UploadedFile[] $images
     */
    public function __construct(
        private string $id,
        private ?string $title = null,
        private ?string $description = null,
        private ?array $images = null,
        private ?array $variants = null,
    )
    {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return UploadedFile[]|null
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     * @return MerchVariantDto[]|null
     */
    public function getVariants(): ?array
    {
        return $this->variants;
    }
}
