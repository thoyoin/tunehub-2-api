<?php

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/api/me', name: 'app_user', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'user' => $user->jsonSerialize(),
        ]);
    }
}
