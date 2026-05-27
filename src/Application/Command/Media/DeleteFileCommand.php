<?php

declare(strict_types=1);

namespace App\Application\Command\Media;

final readonly class DeleteFileCommand
{
    public function __construct(
        private string $fileUrl
    )
    {}

    public function getFileUrl(): string
    {
        return $this->fileUrl;
    }
}
