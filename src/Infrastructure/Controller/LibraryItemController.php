<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Query\LibraryItem\GetLibraryItemByIdQuery;
use App\Application\Query\LibraryItem\GetLibraryItemsQuery;
use App\Application\QueryHandler\LibraryItem\GetLibraryItemByIdQueryHandler;
use App\Application\QueryHandler\LibraryItem\GetLibraryItemsQueryHandler;
use App\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class LibraryItemController extends AbstractController
{
    #[Route('/api/libraryItems', name: 'apiLibraryItems', methods: ['GET'])]
    public function getAll(GetLibraryItemsQueryHandler $handler): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'libraryItems' => $handler(new GetLibraryItemsQuery($user->getId())),
        ]);
    }

    #[Route('/api/libraryItems/{id}', name: 'apiLibraryItem', methods: ['GET'])]
    public function show(int $id, GetLibraryItemByIdQueryHandler $handler): JsonResponse
    {
        return $this->json([
            'libraryItem' => $handler(new GetLibraryItemByIdQuery($id)),
        ]);
    }
}
