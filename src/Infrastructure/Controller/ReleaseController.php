<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\Release\UploadReleaseCommand;
use App\Application\CommandHandler\Release\UploadReleaseCommandHandler;
use App\Application\Query\Release\GetLatestReleasesQuery;
use App\Application\QueryHandler\Release\GetLatestReleasesQueryHandler;
use App\Infrastructure\Request\Release\UploadReleaseRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        $uploadedAudioFiles = $request->files->all('audioFiles');
        $cover = $request->files->get('cover');

        /** @var array<int, UploadedFile> $audioFiles */
        $audioFiles = [];

        foreach ($uploadedAudioFiles as $audioFile) {
            if (!$audioFile instanceof UploadedFile) {
                throw new \InvalidArgumentException('Each audio file must be a valid uploaded file.');
            }

            $audioFiles[] = $audioFile;
        }

        if (!$cover instanceof UploadedFile) {
            throw new \InvalidArgumentException('Cover must be a valid uploaded file.');
        }

        $handler(new UploadReleaseCommand(
            $uploadReleaseRequest->releaseTitle,
            $uploadReleaseRequest->type,
            $uploadReleaseRequest->releaseDate,
            $uploadReleaseRequest->artistId,
            $uploadReleaseRequest->titles,
            $audioFiles,
            $cover,
        ));

        return $this->json([
            'message' => 'Release uploaded',
        ]);
    }
}
