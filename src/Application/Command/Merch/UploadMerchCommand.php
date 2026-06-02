<?php

declare(strict_types=1);

namespace App\Application\Command\Merch;

use App\Application\DTO\Merch\MerchVariantDto;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UploadMerchCommand
{
    /**
     * @param MerchVariantDto[] $merchVariants
     * @param UploadedFile[] $images
     */
    public function __construct(
        private int $userId,
        private string $itemTitle,
        private ?string $itemDescription,
        private ?array $images,
        private array $merchVariants,
    )
    {}

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getItemTitle(): string
    {
        return $this->itemTitle;
    }

    public function getItemDescription(): ?string
    {
        return $this->itemDescription;
    }

    /**
     * @return UploadedFile[]|null
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     * @return MerchVariantDto[]
     */
    public function getMerchVariants(): array
    {
        return $this->merchVariants;
    }
}
