<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\Playlist\CreatePlaylistCommand;
use App\Application\Command\Playlist\DeletePlaylistCommand;
use App\Application\Command\Playlist\UpdatePlaylistCommand;
use App\Application\Command\Playlist\UpdatePlaylistVisibilityCommand;
use App\Application\CommandHandler\Playlist\CreatePlaylistCommandHandler;
use App\Application\CommandHandler\Playlist\DeletePlaylistCommandHandler;
use App\Application\CommandHandler\Playlist\UpdatePlaylistCommandHandler;
use App\Application\CommandHandler\Playlist\UpdatePlaylistVisibilityCommandHandler;
use App\Application\Query\Playlist\GetPlaylistByIdQuery;
use App\Application\QueryHandler\Playlist\GetPlaylistByIdQueryHandler;
use App\Domain\Entity\User;
use App\Domain\ValueObject\PlaylistVisibility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Routing\Attribute\Route;

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
            'libraryItem' => $handler(new CreatePlaylistCommand($user->getId())),
        ]);
    }

    #[Route('/api/playlist/{id}', name: 'playlist_show', methods: ['GET'])]
    public function show(int $id, GetPlaylistByIdQueryHandler $handler): JsonResponse
    {
        return $this->json([
            'playlistItem' => $handler(new GetPlaylistByIdQuery($id))
        ]);
    }

    #[Route('/api/playlist/{id}', name: 'playlist_destroy', methods: ['DELETE'])]
    public function destroy(int $id, DeletePlaylistCommandHandler $handler): JsonResponse
    {
        $handler(new DeletePlaylistCommand($id));

        return $this->json([
            'message' => 'Playlist successfully deleted'
        ]);
    }

    #[Route('/api/playlist/{id}', name: 'playlist_update_visibility', methods: ['PATCH'])]
    public function updateVisibility(
        int $id,
        UpdatePlaylistVisibilityCommandHandler $handler,
        Request $request,
    ): JsonResponse
    {
        return $this->json([
            'visibility' => $handler(
                new UpdatePlaylistVisibilityCommand(
                    $id,
                    PlaylistVisibility::tryFrom($request->toArray()['visibility']),
                )
            ),
        ]);
    }

    #[Route('/api/playlist/{id}', name: 'playlist_update', methods: ['PUT'])]
    public function update(
        int $id,
        #[MapUploadedFile] ?UploadedFile $cover,
        UpdatePlaylistCommandHandler $handler,
        Request $request
    ): JsonResponse
    {
        $data = $request->toArray();

        return $this->json($handler(
            new UpdatePlaylistCommand(
                $id,
                $data['title'],
                $data['description'],
                $cover,
            )
        ));
    }
}
