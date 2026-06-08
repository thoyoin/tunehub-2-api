<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Query\LibraryItem\GetLibraryItemQuery;
use App\Application\Query\LibraryItem\GetLibraryItemsQuery;
use App\Application\Query\QueryBusInterface;
use App\Domain\Entity\LibraryItem;
use App\Domain\Entity\User;
use App\Infrastructure\Security\Voter\LibraryItem\LibraryItemVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class LibraryItemController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    )
    {}

    #[Route('/api/libraryItems', name: 'apiLibraryItems', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'libraryItems' => $this->queryBus->execute(new GetLibraryItemsQuery($user->getId())),
        ]);
    }

    #[Route('/api/libraryItems/{id}', name: 'apiLibraryItem', methods: ['GET'])]
    public function show(LibraryItem $libraryItem): JsonResponse
    {
        $this->denyAccessUnlessGranted(LibraryItemVoter::VIEW, $libraryItem);

        return $this->json([
            'libraryItem' => $this->queryBus->execute(new GetLibraryItemQuery($libraryItem)),
        ]);
    }
}
