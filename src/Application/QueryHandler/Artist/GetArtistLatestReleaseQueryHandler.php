<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Artist;

use App\Application\DTO\Release\ReleasePreviewDto;
use App\Application\Factory\Release\ReleasePreviewDtoFactory;
use App\Application\Query\Artist\GetArtistLatestReleaseQuery;
use App\Domain\Entity\Release;
use App\Domain\Repository\ReleaseRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetArtistLatestReleaseQueryHandler
{
    public function __construct(
        private ReleaseRepositoryInterface $releaseRepository,
        private ReleasePreviewDtoFactory $dtoFactory,
    )
    {}

    public function __invoke(GetArtistLatestReleaseQuery $query): ReleasePreviewDto
    {
        $release = $this->releaseRepository->getArtistLatestPublished($query->getArtist()->getId());

        if (!$release instanceof Release) {
            throw new \DomainException('Release not found');
        }

        return $this->dtoFactory->create($release);
    }
}
