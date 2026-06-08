<?php

namespace App\Infrastructure\Controller;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\User\CreateUserCommand;
use App\Application\Command\User\RefreshAuthTokenCommand;
use App\Application\DTO\Auth\RefreshTokenDto;
use App\Domain\Entity\User;
use App\Infrastructure\Request\User\CreateUserRequest;
use App\Infrastructure\Service\RefreshTokenService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{
    public function __construct(
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly RefreshTokenService $refreshTokenService,
        private readonly CommandBusInterface $commandBus,
    )
    {}

    #[Route('/api/register', name: 'app_auth', methods: ['POST'])]
    public function signUp(
        #[MapRequestPayload] CreateUserRequest $request,
    ): JsonResponse
    {
        try {
            $user = $this->commandBus->execute(new CreateUserCommand(
                $request->username,
                $request->email,
                $request->password,
                $request->passwordConfirmation
            ));
        } catch (\DomainException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $token = $this->jwtManager->create($user);

        $response = new JsonResponse([
            'token' => $token,
            'user' => $user,
        ]);

        $refreshToken = $this->refreshTokenService->create($user);

        $response->headers->setCookie(
            Cookie::create('refresh_token')
            ->withValue($refreshToken)
            ->withHttpOnly(true)
            ->withSecure(true)
            ->withSameSite('lax')
            ->withPath('/api/token/refresh')
            ->withExpires(new \DateTimeImmutable('+3 days'))
        );

        return $response;
    }

    #[Route('/api/token/refresh', name: 'app_auth_refresh', methods: ['POST'])]
    public function refreshToken(Request $request): JsonResponse
    {
        $refreshToken = $request->cookies->get('refresh_token');

        if ($refreshToken === null || $refreshToken === '') {
            return $this->json(['message' => 'Refresh token is missing.'], 401);
        }

        try {
            $result = $this->commandBus->execute(new RefreshAuthTokenCommand($refreshToken));
        } catch (\Throwable) {
            return $this->json(['message' => 'Refresh token failed.'], 401);
        }

        assert($result instanceof RefreshTokenDTO);

        $response = $this->json([
            'token' => $result->getAccessToken(),
            'user' => $result->getUser(),
        ]);

        $response->headers->setCookie(
            Cookie::create('refresh_token')
                ->withValue($result->getRefreshToken())
                ->withHttpOnly(true)
                ->withSecure(true)
                ->withSameSite('lax')
                ->withPath('/api/token/refresh')
                ->withExpires(new \DateTimeImmutable('+3 days'))
        );

        return $response;
    }

    #[Route('/api/auth/logout', name: 'app_auth_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        $response = new JsonResponse(null, 204);

        $response->headers->setCookie(
            Cookie::create('refresh_token')
                ->withValue(null)
                ->withSameSite('lax')
                ->withPath('/api/token/refresh')
                ->withExpires(new \DateTimeImmutable('-1 days'))
        );

        return $response;
    }
}
