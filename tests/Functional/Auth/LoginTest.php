<?php

namespace App\Tests\Functional\Auth;

use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class LoginTest extends WebTestCase
{
    use Factories, ResetDatabase;

    public function testUserCanLogin(): void
    {
        $client = static::createClient();

        $user = UserFactory::createOne([
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
                'email' => $user->getEmail(),
                'password' => 'passWORD123!',
            ])
        );

        self::assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        self::assertIsArray($data);
        self::assertArrayHasKey('token', $data);
    }
}
