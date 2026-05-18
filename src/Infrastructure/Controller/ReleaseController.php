<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Query\Release\GetLatestReleasesQuery;
use App\Application\QueryHandler\Release\GetLatestReleasesQueryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
}
