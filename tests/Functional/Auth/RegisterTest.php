<?php

namespace App\Tests\Functional\Auth;

use App\Infrastructure\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterTest extends WebTestCase
{
    public function testUserCanRegister(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/register',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            content: json_encode([
                'email' => 'test@test.com',
                'username' => 'test',
                'password' => 'passWORD123!',
                'passwordConfirmation' => 'passWORD123!',
            ]));

        self::assertResponseStatusCodeSame(201);

        $data = json_decode($client->getResponse()->getContent(), true);

        self::assertIsArray($data);
        self::assertArrayHasKey('user', $data);

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['email' => 'test@test.com']);

        self::assertNotNull($user);
        self::assertSame('test', $user->getUsername());
        self::assertSame('test@test.com', $user->getEmail());
        self::assertNotSame('passWORD123!', $user->getPassword());
    }
}
