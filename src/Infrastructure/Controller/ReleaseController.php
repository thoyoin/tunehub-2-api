<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\Release\DeleteReleaseCommand;
use App\Application\Command\Release\PublishReleaseCommand;
use App\Application\Command\Release\UpdateReleaseCommand;
use App\Application\Command\Release\UploadReleaseCommand;
use App\Application\CommandHandler\Release\DeleteReleaseCommandHandler;
use App\Application\CommandHandler\Release\PublishReleaseCommandHandler;
use App\Application\CommandHandler\Release\UpdateReleaseCommandHandler;
use App\Application\CommandHandler\Release\UploadReleaseCommandHandler;
use App\Application\Query\Release\GetLatestReleasesQuery;
use App\Application\QueryHandler\Release\GetLatestReleasesQueryHandler;
use App\Domain\Entity\Release;
use App\Domain\Entity\User;
use App\Domain\ValueObject\ReleaseType;
use App\Infrastructure\Request\Release\UpdateReleaseRequest;
use App\Infrastructure\Request\Release\UploadReleaseRequest;
use App\Infrastructure\Security\Voter\Release\ReleaseVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ReleaseController extends AbstractController
{
    #[Route('/api/releases/latest', name: 'releases_latest', methods: ['GET'])]
    public function getLatest(GetLatestReleasesQueryHandler $handler): JsonResponse
    {
        return $this->json([
            'latestReleases' => $handler(new GetLatestReleasesQuery())
        ]);
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[Route('/api/release/upload', name: 'upload_release', methods: ['POST'])]
    public function upload(
        UploadReleaseCommandHandler $handler,
        Request $request,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted(ReleaseVoter::CREATE);

        /** @var array<int, string> $titles */
        $titles = $request->request->all('titles');

        /** @var array<int, UploadedFile> $audioFiles */
        $audioFiles = $request->files->all('audioFiles');

        $cover = $request->files->get('cover');

        if (!$cover instanceof UploadedFile) {
            return $this->json([
                'message' => 'Validation failed',
                'errors' => 'Cover is required.',
            ], 422);
        }

        $uploadReleaseRequest = new UploadReleaseRequest(
            (string) $request->request->get('releaseTitle'),
            ReleaseType::from((string) $request->request->get('type')),
            new \DateTimeImmutable((string) $request->request->get('releaseDate')),
            (int) $request->request->get('artistId'),
            $titles,
            $audioFiles,
            $cover
        );

        $errors = $validator->validate($uploadReleaseRequest);

        if (count($errors) > 0) {
            return $this->json([
                'message' => 'Validation failed',
                'errors' => (string) $errors,
            ], 422);
        }

        $handler(new UploadReleaseCommand(
            $uploadReleaseRequest->releaseTitle,
            $uploadReleaseRequest->type,
            $uploadReleaseRequest->releaseDate,
            $uploadReleaseRequest->artistId,
            $uploadReleaseRequest->titles,
            $uploadReleaseRequest->audioFiles,
            $uploadReleaseRequest->cover,
        ));

        return new JsonResponse(null, 201);
    }

    #[Route('/api/release/{id}', name: 'release_update', methods: ['PUT'])]
    public function update(
        #[MapRequestPayload] UpdateReleaseRequest $request,
        #[MapUploadedFile(
            new Assert\File(
                maxSize: '10M',
                mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
            )
        )] ?UploadedFile $cover,
        Release $release,
        UpdateReleaseCommandHandler $handler,
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted(ReleaseVoter::UPDATE, $release);

        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $handler(new UpdateReleaseCommand($release, $user, $request->releaseTitle, $cover));

        return new JsonResponse(null, 204);
    }

    #[Route('/api/release/{id}/publish', name: 'release_publish', methods: ['PATCH'])]
    public function publish(
        Release $release,
        PublishReleaseCommandHandler $handler,
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted(ReleaseVoter::PUBLISH, $release);

        $handler(new PublishReleaseCommand($release));

        return new JsonResponse(null, 204);
    }

    #[Route('/api/release/{id}', name: 'release_delete', methods: ['DELETE'])]
    public function delete(
        Release $release,
        DeleteReleaseCommandHandler $handler
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted(ReleaseVoter::DELETE, $release);

        $handler(new DeleteReleaseCommand($release));

        return new JsonResponse(null, 204);
    }
}
