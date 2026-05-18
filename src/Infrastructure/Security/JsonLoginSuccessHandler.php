<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\Entity\User;
use App\Infrastructure\Service\RefreshTokenService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

readonly class JsonLoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private RefreshTokenService $refreshTokenService,
    )
    {}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return new JsonResponse([
                'message' => 'Invalid authenticated user.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $accessToken = $this->jwtManager->create($user);
        $refreshToken = $this->refreshTokenService->create($user);

        $response = new JsonResponse([
            'token' => $accessToken,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getUserIdentifier(),
                'roles' => $user->getRoles(),
            ],
        ]);

        $response->headers->setCookie(
            Cookie::create('refresh_token')
                ->withValue($refreshToken)
                ->withHttpOnly(true)
                ->withSecure(false)
                ->withSameSite('lax')
                ->withPath('/api/token/refresh')
                ->withExpires(new \DateTimeImmutable('+3 days'))
        );

        return $response;
    }
}
