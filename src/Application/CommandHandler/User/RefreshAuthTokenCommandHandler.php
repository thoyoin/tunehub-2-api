<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\User;

use App\Application\Command\User\RefreshAuthTokenCommand;
use App\Application\DTO\Auth\RefreshTokenDto;
use App\Application\DTO\User\UserDto;
use App\Application\Factory\User\UserDtoFactory;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Service\RefreshTokenService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RefreshAuthTokenCommandHandler
{
    public function __construct(
        private RefreshTokenService $refreshTokenService,
        private UserRepository $userRepository,
        private JWTTokenManagerInterface $jwtManager,
        private UserDtoFactory $dtoFactory,
    )
    {}

    public function __invoke(RefreshAuthTokenCommand $command): RefreshTokenDto
    {
        $payload = $this->refreshTokenService->parse($command->getRefreshToken());
        $user = $this->userRepository->find($payload->getSub());

        if ($user === null) {
            throw new \RuntimeException('User not found.');
        }

        return new RefreshTokenDto(
            accessToken: $this->jwtManager->create($user),
            refreshToken: $this->refreshTokenService->create($user),
            user: $this->dtoFactory->create($user),
        );
    }
}
