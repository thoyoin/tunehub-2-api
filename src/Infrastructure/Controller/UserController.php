<?php

namespace App\Infrastructure\Controller;

use App\Application\Command\User\UpdateUserCommand;
use App\Application\CommandHandler\User\UpdateUserCommandHandler;
use App\Application\Factory\User\UserDtoFactory;
use App\Domain\Entity\User;
use App\Infrastructure\Request\User\UpdateUserRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;

final class UserController extends AbstractController
{
    #[Route('/api/me', name: 'app_user', methods: ['GET'])]
    public function me(
        UserDtoFactory $dtoFactory,
    ): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json([
            'user' => $dtoFactory->create($user),
        ]);
    }

    #[Route('/api/me/update', name: 'app_user_update', methods: ['POST'])]
    public function update(
        UpdateUserCommandHandler $handler,
        #[MapRequestPayload] UpdateUserRequest $request,
        #[MapUploadedFile(
            constraints: [
                new Assert\Image(
                    maxSize: '5M',
                    mimeTypes: [
                        'image/jpeg',
                        'image/png',
                        'image/webp'
                    ],
                    mimeTypesMessage: 'Please upload a valid image file.',
                )
            ]
        )] ?UploadedFile $profilePicture = null,
    ): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $handler(new UpdateUserCommand(
            $user->getId(),
            $request->username,
            $request->email,
            $profilePicture,
        ));

        return new JsonResponse(null, 204);
    }
}
