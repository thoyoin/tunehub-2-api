<?php

namespace App\Tests\Factory;

use App\Domain\Entity\Playlist;
use App\Domain\ValueObject\PlaylistVisibility;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Playlist>
 */
final class PlaylistFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(
        #[Autowire('%media.default_cover%')]
        readonly private string $defaultCover,
    )
    {
        parent::__construct();
    }

    #[\Override]
    public static function class(): string
    {
        return Playlist::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'cover_url' => $this->defaultCover,
            'itemType' => 'playlist',
            'owner' => UserFactory::new(),
            'title' => self::faker()->text(55),
            'visibility' => PlaylistVisibility::Public,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Playlist $playlist): void {})
        ;
    }
}
