<?php

declare(strict_types=1);

namespace App\Infrastructure\ArgumentResolver;

use App\Domain\ValueObject\ReleaseType;
use App\Infrastructure\Request\Release\UploadReleaseRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class UploadReleaseRequestResolver implements ValueResolverInterface
{
    public function __construct(
        private ValidatorInterface $validator,
    )
    {}

    /**
     * @return array<int, UploadReleaseRequest>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== UploadReleaseRequest::class) {
            return [];
        }

        /** @var array<int, string> $titles */
        $titles = $request->request->all('titles');

        /** @var array<int, UploadedFile> $audioFiles */
        $audioFiles = $request->files->all('audioFiles');

        $cover = $request->files->get('cover');

        if (!$cover instanceof UploadedFile) {
            return [];
        }

        try {
            $uploadReleaseRequest = new UploadReleaseRequest(
                (string) $request->request->get('releaseTitle'),
                ReleaseType::from((string) $request->request->get('type')),
                new \DateTimeImmutable((string) $request->request->get('releaseDate')),
                (int) $request->request->get('artistId'),
                $titles,
                $audioFiles,
                $cover
            );
        } catch (\Throwable $e) {
            throw new BadRequestHttpException('Invalid request data format.', $e);
        }

        $errors = $this->validator->validate($uploadReleaseRequest);

        if (count($errors) > 0) {
            throw new BadRequestHttpException((string) $errors);
        }

        return [$uploadReleaseRequest];
    }
}
