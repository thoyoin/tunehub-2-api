<?php

declare(strict_types=1);

namespace App\Tests\Functional\Playlist;

use App\Domain\ValueObject\PlaylistVisibility;
use App\Tests\Factory\PlaylistFactory;
use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class OwnershipTest extends WebTestCase
{
    use ResetDatabase;

    public function testNonOwnerCannotViewPrivatePlaylist(): void
    {
        $client = static::createClient();

        $playlist = PlaylistFactory::createOne([
            'visibility' => PlaylistVisibility::Private
        ]);

        $nonOwner = UserFactory::createOne([
            'password' => 'passWORD123!'
        ]);

        $client->request(
            'POST',
            '/api/login',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            content: json_encode([
                    'email' => $nonOwner->getEmail(),
                    'password' => 'passWORD123!',
                ])
            );

        $data = json_decode($client->getResponse()->getContent(), true);

        $token = $data['token'];

        $client->request(
            'GET',
            sprintf('/api/playlist/%d', $playlist->getId()),
            server: [
                'HTTP_Authorization' => 'Bearer ' . $token,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        self::assertResponseStatusCodeSame(403);
    }
}
