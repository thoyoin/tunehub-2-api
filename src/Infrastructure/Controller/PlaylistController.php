<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\Playlist\AddTrackToPlaylistCommand;
use App\Application\Command\Playlist\CreatePlaylistCommand;
use App\Application\Command\Playlist\DeletePlaylistCommand;
use App\Application\Command\Playlist\UpdatePlaylistCommand;
use App\Application\Command\Playlist\UpdatePlaylistVisibilityCommand;
use App\Application\CommandHandler\Playlist\AddTrackToPlaylistCommandHandler;
use App\Application\CommandHandler\Playlist\CreatePlaylistCommandHandler;
use App\Application\CommandHandler\Playlist\DeletePlaylistCommandHandler;
use App\Application\CommandHandler\Playlist\UpdatePlaylistCommandHandler;
use App\Application\CommandHandler\Playlist\UpdatePlaylistVisibilityCommandHandler;
use App\Application\Query\Playlist\GetAllPlaylistsQuery;
use App\Application\Query\Playlist\GetPlaylistQuery;
use App\Application\Query\Track\CheckTrackPresenceQuery;
use App\Application\QueryHandler\Playlist\GetAllPlaylistsQueryHandler;
use App\Application\QueryHandler\Playlist\GetPlaylistQueryHandler;
use App\Application\QueryHandler\Track\CheckTrackPresenceQueryHandler;
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
    #[Route('/api/playlist', name: 'playlist', methods: ['POST'])]
    public function store(CreatePlaylistCommandHandler $handler): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'libraryItem' => $handler(new CreatePlaylistCommand((int)$user->getId())),
        ]);
    }

    #[Route('/api/playlist/{id}', name: 'playlist_show', methods: ['GET'])]
    public function show(Playlist $playlist, GetPlaylistQueryHandler $handler): JsonResponse
    {
        $this->denyAccessUnlessGranted(PlaylistVoter::VIEW, $playlist);

        return $this->json([
            'playlistItem' => $handler(new GetPlaylistQuery($playlist))
        ]);
    }

    #[Route('/api/playlist/{id}', name: 'playlist_destroy', methods: ['DELETE'])]
    public function destroy(Playlist $playlist, DeletePlaylistCommandHandler $handler): JsonResponse
    {
        $this->denyAccessUnlessGranted(PlaylistVoter::DESTROY, $playlist);

        $handler(new DeletePlaylistCommand($playlist));

        return $this->json([
            'message' => 'Playlist successfully deleted'
        ]);
    }

    #[Route('/api/playlist/{id}', name: 'playlist_update_visibility', methods: ['PATCH'])]
    public function updateVisibility(
        Playlist $playlist,
        UpdatePlaylistVisibilityCommandHandler $handler,
        #[MapRequestPayload] UpdatePlaylistVisibilityRequest $request,
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted(PlaylistVoter::EDIT, $playlist);

        return $this->json([
            'visibility' => $handler(
                new UpdatePlaylistVisibilityCommand(
                    $playlist,
                    PlaylistVisibility::from($request->visibility),
                )
            ),
        ]);
    }

    #[Route('/api/playlist/{id}', name: 'playlist_update', methods: ['POST'])]
    public function update(
        Playlist $playlist,
        UpdatePlaylistCommandHandler $handler,
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

        $handler(new UpdatePlaylistCommand(
            $playlist,
            $request->title,
            $request->description,
            $cover
        ));

        return new JsonResponse(null, 204);
    }

    #[Route('/api/playlists', name: 'user_playlists', methods: ['GET'])]
    public function getAll(GetAllPlaylistsQueryHandler $handler): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'playlists' => $handler(new GetAllPlaylistsQuery($user)),
        ]);
    }

    #[Route('/api/playlist/{playlist}/track/{track}', name: 'playlist_add_track', methods: ['POST'])]
    public function addTrack(
        Playlist $playlist,
        Track $track,
        AddTrackToPlaylistCommandHandler $handler
    ): JsonResponse
    {
        $handler(new AddTrackToPlaylistCommand($playlist, $track));

        return new JsonResponse(null, 204);
    }

    #[Route('/api/playlists/contain-tracks', name: 'playlist_contains_tracks', methods: ['GET'])]
    public function checkTracksInPlaylist(
        #[MapQueryString] CheckTracksInPlaylistRequest $request,
        CheckTrackPresenceQueryHandler $handler
    ): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $trackIds = explode(',', $request->track_ids);

        return $this->json([
            'trackPlaylistMap' => $handler(new CheckTrackPresenceQuery($user, $trackIds))
        ]);
    }
}
