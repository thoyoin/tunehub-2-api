<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class JsonLoginFailureHandler implements AuthenticationFailureHandlerInterface
{

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Invalid credentials.',
            'errors' => [
                'general' => [
                    'Invalid email or password.',
                ]
            ]
        ], 401);
    }
}
