<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Release;

use App\Application\DTO\Release\ReleasePreviewDto;
use App\Application\Factory\Release\ReleasePreviewDtoFactory;
use App\Application\Query\Merch\GetArtistMerchQuery;
use App\Application\Query\Release\GetArtistReleasesQuery;
use App\Domain\Repository\ReleaseRepositoryInterface;
use App\Infrastructure\Repository\ReleaseRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetArtistReleasesQueryHandler
{
    public function __construct(
        private ReleaseRepositoryInterface $releaseRepository,
        private ReleasePreviewDtoFactory $dtoFactory,
    )
    {}

    /**
     * @return array<int, ReleasePreviewDto>
     */
    public function __invoke(GetArtistReleasesQuery $query): array
    {
        $releases = $this->releaseRepository->getByArtist($query->artist);

        if ($releases === []) {
            return [];
        }

        $dtos = [];

        foreach ($releases as $release) {
            $dtos[] = $this->dtoFactory->create($release);
        }

        return $dtos;
    }
}
