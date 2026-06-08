<?php

declare(strict_types=1);

namespace App\Application\DTO\Merch;

final readonly class MerchDto
{
    /**
     * @param array<int, MerchVariantDto> $variants
     */
    public function __construct(
        private string $id,
        private bool $isActive,
        private string $title,
        private array $variants,
        private string $userId,
        private ?string $cover = null,
        private ?string $description = null,
    )
    {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return MerchVariantDto[]
     */
    public function getVariants(): array
    {
        return $this->variants;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
