<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\Merch\UploadMerchCommand;
use App\Application\CommandHandler\Merch\UploadMerchCommandHandler;
use App\Application\DTO\Merch\MerchVariantDto;
use App\Application\Query\Merch\GetArtistMerchQuery;
use App\Application\Query\Release\GetArtistReleasesQuery;
use App\Application\Query\Track\GetArtistTracksQuery;
use App\Application\QueryHandler\Merch\GetArtistMerchQueryHandler;
use App\Application\QueryHandler\Release\GetArtistReleasesQueryHandler;
use App\Application\QueryHandler\Track\GetArtistTracksQueryHandler;
use App\Domain\Entity\User;
use App\Infrastructure\Request\Merch\UploadMerchRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/artist')]
class ArtistStudioController extends AbstractController
{
    #[Route('/tracks', name: 'api_artist_tracks', methods: ['GET'])]
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

    #[Route('/releases', name: 'api_artist_releases', methods: ['GET'])]
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

    #[Route('/merch/upload', name: 'api_artist_upload_merch', methods: ['POST'])]
    public function dropMerch(
        UploadMerchRequest $request,
        UploadMerchCommandHandler $handler,
    ): JsonResponse
    {
        $merchVariants = [];

        foreach($request->merchVariants as $variantRequest) {
            $merchVariants[] = new MerchVariantDto(
                $variantRequest->variantName,
                $variantRequest->price,
                $variantRequest->stock,
            );
        }

        $user = $this->getUser();

        if (!$user instanceof User || $user->getId() === null) {
            throw $this->createAccessDeniedException();
        }

        $handler(new UploadMerchCommand(
            $user->getId(),
            $request->itemTitle,
            $request->itemDescription,
            $request->images,
            $merchVariants,
        ));

        return new JsonResponse(null, 204);
    }

    #[Route('/merch', name: 'api_artist_get_merch', methods: ['GET'])]
    public function getMerch(
        GetArtistMerchQueryHandler $handler,
    ): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User || $user->getId() === null) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'merch' => $handler(new GetArtistMerchQuery($user->getId())),
        ]);
    }
}
