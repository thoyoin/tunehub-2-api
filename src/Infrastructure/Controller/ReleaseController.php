<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Release\DeleteReleaseCommand;
use App\Application\Command\Release\PublishReleaseCommand;
use App\Application\Command\Release\UpdateReleaseCommand;
use App\Application\Command\Release\UploadReleaseCommand;
use App\Application\Query\QueryBusInterface;
use App\Application\Query\Release\GetLatestReleasesQuery;
use App\Application\Query\Release\GetReleaseQuery;
use App\Domain\Entity\Release;
use App\Domain\Entity\User;
use App\Infrastructure\Request\Release\UpdateReleaseRequest;
use App\Infrastructure\Request\Release\UploadReleaseRequest;
use App\Infrastructure\Security\Voter\Release\ReleaseVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;

class ReleaseController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    )
    {}

    #[Route('/api/releases/latest', name: 'releases_latest', methods: ['GET'])]
    public function getLatest(): JsonResponse
    {
        return $this->json([
            'latestReleases' => $this->queryBus->execute(new GetLatestReleasesQuery())
        ]);
    }

    #[Route('/api/release/upload', name: 'upload_release', methods: ['POST'])]
    public function upload(UploadReleaseRequest $uploadReleaseRequest): JsonResponse
    {
        $this->denyAccessUnlessGranted(ReleaseVoter::CREATE);

        $this->commandBus->execute(new UploadReleaseCommand(
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
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted(ReleaseVoter::UPDATE, $release);

        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $this->commandBus->execute(new UpdateReleaseCommand(
            $release,
            $user,
            $request->releaseTitle,
            $cover
        ));

        return new JsonResponse(null, 204);
    }

    #[Route('/api/release/{id}/publish', name: 'release_publish', methods: ['PATCH'])]
    public function publish(Release $release): JsonResponse
    {
        $this->denyAccessUnlessGranted(ReleaseVoter::PUBLISH, $release);

        $this->commandBus->execute(new PublishReleaseCommand($release));

        return new JsonResponse(null, 204);
    }

    #[Route('/api/release/{id}', name: 'release_delete', methods: ['DELETE'])]
    public function delete(Release $release): JsonResponse
    {
        $this->denyAccessUnlessGranted(ReleaseVoter::DELETE, $release);

        $this->commandBus->execute(new DeleteReleaseCommand($release));

        return new JsonResponse(null, 204);
    }

    #[Route('/api/release/{id}', name: 'release_show', methods: ['GET'])]
    public function show(Release $release): JsonResponse
    {
        return $this->json([
            'release' => $this->queryBus->execute(new GetReleaseQuery($release))
        ]);
    }
}
