<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\User;

use App\Application\Command\User\RefreshAuthTokenCommand;
use App\Application\DTO\User\UserDto;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Service\RefreshTokenService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final readonly class RefreshAuthTokenCommandHandler
{
    public function __construct(
        private RefreshTokenService $refreshTokenService,
        private UserRepository $userRepository,
        private JWTTokenManagerInterface $jwtManager,
    )
    {}

    public function handle(string $refreshToken): RefreshAuthTokenCommand
    {
        $payload = $this->refreshTokenService->parse($refreshToken);
        $user = $this->userRepository->find($payload->getSub());

        if ($user === null) {
            throw new \RuntimeException('User not found.');
        }

        return new RefreshAuthTokenCommand(
            accessToken: $this->jwtManager->create($user),
            refreshToken: $this->refreshTokenService->create($user),
            user: new UserDto(
                $user->getId(),
                $user->getUsername(),
                $user->getSlug(),
                $user->getUserIdentifier(),
                $user->getProfilePicture(),
                $user->getRole(),
            ),
        );
    }
}
