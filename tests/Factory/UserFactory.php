<?php

declare(strict_types=1);

namespace App\Tests\Factory;


use App\Domain\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

class UserFactory extends PersistentObjectFactory
{
    public function __construct(
        readonly private UserPasswordHasherInterface $hasher,

        #[Autowire('%media.default_profile_picture%')]
        readonly private string $defaultProfilePicture,
    )
    {
        parent::__construct();
    }

    protected function defaults(): array|callable
    {
        return [
            'username' => self::faker()->firstName(),
            'email' => self::faker()->email(),
            'password' => 'passWORD123!',
            'profile_picture' => $this->defaultProfilePicture,
        ];
    }

    public function initialize(): static
    {
        return $this->afterInstantiate(function (User $user) {
            $user->setPassword(
                $this->hasher->hashPassword(
                    $user,
                    $user->getPassword(),
                )
            );
        });
    }

    public static function class(): string
    {
        return User::class;
    }
}
