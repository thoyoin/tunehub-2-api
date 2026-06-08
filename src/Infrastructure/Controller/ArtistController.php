<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Query\Artist\GetArtistLatestReleaseQuery;
use App\Application\Query\Artist\GetArtistTopTracksQuery;
use App\Application\Query\Release\GetArtistReleasesQuery;
use App\Application\Query\User\GetUserQuery;
use App\Domain\Entity\User;
use App\Infrastructure\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/artist')]
class ArtistController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
    )
    {}

    #[Route('/{id}', methods: ['GET'])]
    public function getArtist(
        User $user,
    ): JsonResponse
    {
        return $this->json([
            'artist' => $this->queryBus->execute(new GetUserQuery($user))
        ]);
    }

    #[Route('/{id}/releases/latest', methods: ['GET'])]
    public function getLatestRelease(
        User $user,
    ): JsonResponse
    {
        return $this->json([
            'latestRelease' => $this->queryBus->execute(new GetArtistLatestReleaseQuery($user)),
        ]);
    }

    #[Route('/{id}/top-tracks', methods: ['GET'])]
    public function getTopTracks(
        User $user,
    ): JsonResponse
    {
        return $this->json([
            'topTracks' => $this->queryBus->execute(new GetArtistTopTracksQuery($user)),
        ]);
    }

    #[Route('/{id}/releases', methods: ['GET'])]
    public function getReleases(User $user): JsonResponse
    {
        return $this->json([
            'releases' => $this->queryBus->execute(new GetArtistReleasesQuery($user)),
        ]);
    }
}
