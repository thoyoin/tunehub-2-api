<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\User;

use App\Application\DTO\User\UserDto;
use App\Application\Factory\User\UserDtoFactory;
use App\Application\Query\User\GetUserQuery;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetUserQueryHandler
{
    public function __construct(
        private UserDtoFactory $dtoFactory,
    )
    {}

    public function __invoke(GetUserQuery $query): UserDto
    {
        return $this->dtoFactory->create($query->getUser());
    }
}
