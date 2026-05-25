<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Zenstruck\Foundry\Test\Factories;

class RefreshTokenTest extends WebTestCase
{
    use Factories;

    public function testUserCanRefreshToken(): void
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

        $refreshCookie = $client->getResponse()->headers->getCookies()[0] ?? null;

        self::assertNotNull($refreshCookie);
        self::assertSame('refresh_token', $refreshCookie->getName());

        $client->getCookieJar()->set(new Cookie(
            $refreshCookie->getName(),
            $refreshCookie->getValue(),
            $refreshCookie->getExpiresTime(),
            $refreshCookie->getPath(),
            $refreshCookie->getDomain() ?? 'localhost',
            $refreshCookie->isSecure(),
            $refreshCookie->isHttpOnly(),
        ));

        $client->request(
            'POST',
            'https://localhost/api/token/refresh',
            server: [
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        self::assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        self::assertIsArray($data);
        self::assertArrayHasKey('token', $data);
        self::assertArrayHasKey('user', $data);
    }
}
