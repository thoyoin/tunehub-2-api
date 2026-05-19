<?php

namespace App\Infrastructure\Controller;

use App\Application\Command\User\UpdateUserCommand;
use App\Application\CommandHandler\User\UpdateUserCommandHandler;
use App\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/api/me', name: 'app_user', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'user' => $user,
        ]);
    }

    #[Route('/api/me/update', name: 'app_user_update', methods: ['POST'])]
    public function update(
        UpdateUserCommandHandler $handler,
        Request $request,
    ): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json($handler(new UpdateUserCommand(
            $user->getId(),
            $request->request->get('username'),
            $request->request->get('email'),
            $request->files->get('profilePicture'),
        )));
    }
}
