<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

trait TestClientTrait
{
    protected function createTestHttpClient(array $queue): ClientInterface
    {
        return new Client($this->createTestHttpClientConfig($queue));
    }

    protected function createTestHttpClientConfig(array $queue): array
    {
        $mockHandler = new MockHandler($queue);
        $handlerStack = HandlerStack::create($mockHandler);

        return ['handler' => $handlerStack];
    }
}
