<?php

declare(strict_types=1);

namespace App\Infrastructure\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpClient\Exception\ClientException;

readonly class ShopwareClient
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private CacheInterface $cache,
        private string $shopwareApiUrl,
        private string $clientId,
        private string $clientSecret
    ) {}

    /**
     * @param array{
     *   headers?: array<string, string>,
     *   json?: array<string, mixed>
     * } $options
     *
     * @return array<string, mixed>
     */
    public function request(string $method, string $endpoint, array $options = []): array
    {
        $token = $this->getAccessToken();

        $url = rtrim($this->shopwareApiUrl, '/') . '/api/' . ltrim($endpoint, '/');

        $options['headers']['Authorization'] = 'Bearer ' . $token;
        $options['headers']['Accept'] = 'application/json';
        $options['headers']['Content-Type'] = 'application/json';

        $response = $this->httpClient->request($method, $url, $options);

        if ($response->getStatusCode() === 204) {
            return [];
        }

        /** @var array<string, mixed> $data */
        $data = $response->toArray();

        return $data;
    }

    private function getAccessToken(): string
    {
        return $this->cache->get('shopware_admin_token', function (ItemInterface $item) {
            $url = rtrim($this->shopwareApiUrl, '/') . '/api/oauth/token';

            $response = $this->httpClient->request('POST', $url, [
                'json' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]
            ]);

            /** @var array<string, mixed> $data */
            $data = $response->toArray();

            $item->expiresAfter(540);

            $accessToken = $data['access_token'] ?? '';

            if (!is_string($accessToken) || $accessToken === '') {
                throw new \RuntimeException('Failed to retrieve a valid Shopware access token.');
            }

            return $accessToken;
        });
    }
}
