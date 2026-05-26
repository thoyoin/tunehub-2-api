<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\Release\UploadReleaseCommand;
use App\Application\CommandHandler\Release\UploadReleaseCommandHandler;
use App\Application\Query\Release\GetLatestReleasesQuery;
use App\Application\QueryHandler\Release\GetLatestReleasesQueryHandler;
use App\Infrastructure\Request\Release\UploadReleaseRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ReleaseController extends AbstractController
{
    #[Route('/api/releases/latest', name: 'releases_latest', methods: ['GET'])]
    public function getLatest(GetLatestReleasesQueryHandler $handler): JsonResponse
    {
        return $this->json([
            'latestReleases' => $handler(new GetLatestReleasesQuery())
        ]);
    }

    #[Route('/api/release/upload', name: 'releases', methods: ['POST'])]
    public function uploadRelease(
        #[MapRequestPayload] UploadReleaseRequest $uploadReleaseRequest,
        UploadReleaseCommandHandler $handler,
        Request $request,
    ): JsonResponse
    {
        $handler(new UploadReleaseCommand(
            $uploadReleaseRequest->releaseTitle,
            $uploadReleaseRequest->type,
            $uploadReleaseRequest->releaseDate,
            $uploadReleaseRequest->artistId,
            $uploadReleaseRequest->titles,
            $request->files->all('audioFiles'),
            $request->files->get('cover'),
        ));

        return $this->json([
            'message' => 'Release uploaded',
        ]);
    }
}
