<?php

namespace App\Infrastructure\Controller;

use App\Application\Command\User\UpdateUserCommand;
use App\Application\CommandHandler\User\UpdateUserCommandHandler;
use App\Domain\Entity\User;
use App\Infrastructure\Request\User\UpdateUserRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
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
        #[MapRequestPayload] UpdateUserRequest $request,
    ): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json($handler(new UpdateUserCommand(
            $request->userId,
            $request->username,
            $request->email,
            $request->profilePicture,
        )));
    }
}
