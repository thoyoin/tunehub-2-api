<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Playlist\AddTrackToPlaylistCommand;
use App\Application\Command\Playlist\CreatePlaylistCommand;
use App\Application\Command\Playlist\DeletePlaylistCommand;
use App\Application\Command\Playlist\UpdatePlaylistCommand;
use App\Application\Command\Playlist\UpdatePlaylistVisibilityCommand;
use App\Application\Query\Playlist\GetAllPlaylistsQuery;
use App\Application\Query\Playlist\GetPlaylistQuery;
use App\Application\Query\QueryBusInterface;
use App\Application\Query\Track\CheckTrackPresenceQuery;
use App\Domain\Entity\Playlist;
use App\Domain\Entity\Track;
use App\Domain\Entity\User;
use App\Domain\ValueObject\PlaylistVisibility;
use App\Infrastructure\Request\Playlist\CheckTracksInPlaylistRequest;
use App\Infrastructure\Request\Playlist\UpdatePlaylistRequest;
use App\Infrastructure\Request\Playlist\UpdatePlaylistVisibilityRequest;
use App\Infrastructure\Security\Voter\Playlist\PlaylistVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;

class PlaylistController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    )
    {}

    #[Route('/api/playlist', name: 'playlist', methods: ['POST'])]
    public function store(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'libraryItem' => $this->commandBus->execute(new CreatePlaylistCommand($user->getId())),
        ]);
    }

    #[Route('/api/playlist/{id}', name: 'playlist_show', methods: ['GET'])]
    public function show(Playlist $playlist): JsonResponse
    {
        $this->denyAccessUnlessGranted(PlaylistVoter::VIEW, $playlist);

        return $this->json([
            'playlistItem' => $this->queryBus->execute(new GetPlaylistQuery($playlist))
        ]);
    }

    #[Route('/api/playlist/{id}', name: 'playlist_destroy', methods: ['DELETE'])]
    public function destroy(Playlist $playlist): JsonResponse
    {
        $this->denyAccessUnlessGranted(PlaylistVoter::DESTROY, $playlist);

        $this->commandBus->execute(new DeletePlaylistCommand($playlist));

        return new JsonResponse(null, 204);
    }

    #[Route('/api/playlist/{id}', name: 'playlist_update_visibility', methods: ['PATCH'])]
    public function updateVisibility(
        Playlist $playlist,
        #[MapRequestPayload] UpdatePlaylistVisibilityRequest $request,
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted(PlaylistVoter::EDIT, $playlist);

        return $this->json([
            'visibility' => $this->commandBus->execute(new UpdatePlaylistVisibilityCommand(
                $playlist,
                PlaylistVisibility::from($request->visibility),
            ))
        ]);
    }

    #[Route('/api/playlist/{id}', name: 'playlist_update', methods: ['POST'])]
    public function update(
        Playlist $playlist,
        #[MapRequestPayload] UpdatePlaylistRequest $request,
        #[MapUploadedFile(
            new Assert\File(
                maxSize: '5M',
                mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
            )
        )] ?UploadedFile $cover,
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted(PlaylistVoter::EDIT, $playlist);

        $this->commandBus->execute(new UpdatePlaylistCommand(
            $playlist,
            $request->title,
            $request->description,
            $cover
        ));

        return new JsonResponse(null, 204);
    }

    #[Route('/api/playlists', name: 'user_playlists', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'playlists' => $this->queryBus->execute(new GetAllPlaylistsQuery($user)),
        ]);
    }

    #[Route('/api/playlist/{playlist}/track/{track}', name: 'playlist_add_track', methods: ['POST'])]
    public function addTrack(
        Playlist $playlist,
        Track $track,
    ): JsonResponse
    {
        $this->commandBus->execute(new AddTrackToPlaylistCommand($playlist, $track));

        return new JsonResponse(null, 204);
    }

    #[Route('/api/playlists/contain-tracks', name: 'playlist_contains_tracks', methods: ['GET'])]
    public function checkTracksInPlaylist(
        #[MapQueryString] CheckTracksInPlaylistRequest $request
    ): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $trackIds = explode(',', $request->track_ids);

        return $this->json([
            'trackPlaylistMap' => $this->queryBus->execute(new CheckTrackPresenceQuery($user, $trackIds))
        ]);
    }
}
