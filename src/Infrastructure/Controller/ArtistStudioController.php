<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Merch\DeleteMerchCommand;
use App\Application\Command\Merch\UpdateMerchCommand;
use App\Application\Command\Merch\UploadMerchCommand;
use App\Application\DTO\Merch\MerchVariantDto;
use App\Application\Query\Merch\GetArtistMerchQuery;
use App\Application\Query\QueryBusInterface;
use App\Application\Query\Release\GetArtistReleasesQuery;
use App\Application\Query\Track\GetArtistTracksQuery;
use App\Domain\Entity\User;
use App\Infrastructure\Request\Merch\UpdateMerchRequest;
use App\Infrastructure\Request\Merch\UploadMerchRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/artist-studio')]
class ArtistStudioController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    )
    {}

    #[Route('/tracks', name: 'api_artist_tracks', methods: ['GET'])]
    public function getTracks(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'tracks' => $this->queryBus->execute(new GetArtistTracksQuery($user)),
        ]);
    }

    #[Route('/releases', name: 'api_artist_releases', methods: ['GET'])]
    public function getReleases(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'releases' => $this->queryBus->execute(new GetArtistReleasesQuery($user)),
        ]);
    }

    #[Route('/merch/upload', name: 'api_artist_upload_merch', methods: ['POST'])]
    public function dropMerch(UploadMerchRequest $request): JsonResponse
    {
        $merchVariants = [];

        foreach($request->merchVariants as $variantRequest) {
            $merchVariants[] = new MerchVariantDto(
                bin2hex(random_bytes(16)),
                $variantRequest->variantName,
                $variantRequest->price,
                $variantRequest->stock,
            );
        }

        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $this->commandBus->execute(new UploadMerchCommand(
            $user->getId(),
            $request->itemTitle,
            $request->itemDescription,
            $request->images,
            $merchVariants,
        ));

        return new JsonResponse(null, 204);
    }

    #[Route('/merch', name: 'api_artist_get_merch', methods: ['GET'])]
    public function getMerch(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'merch' => $this->queryBus->execute(new GetArtistMerchQuery($user->getId())),
        ]);
    }

    #[Route('/merch/{id}/update', name: 'api_artist_update_merch', methods: ['PUT'])]
    public function updateMerch(UpdateMerchRequest $request): JsonResponse
    {
        $merchVariants = [];

        $variants = $request->merchVariants;

        if ($variants !== null) {
            foreach($variants as $variant) {
                $merchVariants[] = new MerchVariantDto(
                    $variant->id,
                    $variant->variantName,
                    $variant->price,
                    $variant->stock,
                );
            }
        }

        $this->commandBus->execute(new UpdateMerchCommand(
            $request->id,
            $request->itemTitle,
            $request->itemDescription,
            $request->images,
            $merchVariants,
        ));

        return new JsonResponse(null, 204);
    }

    #[Route('/merch/{id}/delete', name: 'api_artist_delete_merch', methods: ['DELETE'])]
    public function deleteMerch(string $id): JsonResponse
    {
        $this->commandBus->execute(new DeleteMerchCommand($id));

        return new JsonResponse(null, 204);
    }
}
