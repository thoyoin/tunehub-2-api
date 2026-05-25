<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class MinioService
{
    public function __construct(
        private FilesystemOperator $minioStorage,
    )
    {}

    public function storeProfilePicture(UploadedFile $file): string
    {
        $fileName = sprintf(
            'avatar_%s.%s',
            bin2hex(random_bytes(16)),
            $file->guessExtension()
        );

        $filePath = 'profilePictures/' . $fileName;

        $stream = fopen($file->getPathname(), 'r');

        $this->minioStorage->writeStream($filePath, $stream);

        $url = $this->minioStorage->publicUrl($filePath);

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $url;
    }

    public function storeCover(UploadedFile $file): string
    {
        $fileName = sprintf(
            'cover_%s.%s',
            bin2hex(random_bytes(16)),
            $file->guessExtension()
        );

        $filePath = 'covers/' . $fileName;

        $stream = fopen($file->getPathname(), 'r');

        if ($stream === false) {
            throw new \RuntimeException('Cannot open uploaded file.');
        }

        try {
            $this->minioStorage->writeStream($filePath, $stream);

            $url = $this->minioStorage->publicUrl($filePath);
        } finally {
            fclose($stream);
        }


        return $url;
    }
}
