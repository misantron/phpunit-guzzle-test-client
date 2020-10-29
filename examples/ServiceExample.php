<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient\Example;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class ServiceExample
{
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function methodOne(): ResponseInterface
    {
        return $this->client->request('GET', '/path-one');
    }

    public function methodTwo(array $payload): ResponseInterface
    {
        return $this->client->request('POST', '/path-two', [
            RequestOptions::JSON => $payload,
        ]);
    }
}
