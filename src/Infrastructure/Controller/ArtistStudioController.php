<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Query\Release\GetArtistReleasesQuery;
use App\Application\Query\Track\GetArtistTracksQuery;
use App\Application\QueryHandler\Release\GetArtistReleasesQueryHandler;
use App\Application\QueryHandler\Track\GetArtistTracksQueryHandler;
use App\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ArtistStudioController extends AbstractController
{
    #[Route('/api/artist/tracks', name: 'api_artist_tracks', methods: ['GET'])]
    public function getTracks(GetArtistTracksQueryHandler $handler): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'tracks' => $handler(new GetArtistTracksQuery($user)),
        ]);
    }

    #[Route('/api/artist/releases', name: 'api_artist_releases', methods: ['GET'])]
    public function getReleases(GetArtistReleasesQueryHandler $handler): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'releases' => $handler(new GetArtistReleasesQuery($user)),
        ]);
    }
}
