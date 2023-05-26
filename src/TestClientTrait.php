<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

trait TestClientTrait
{
    public function createTestHttpClient(array $queue, array $config = []): ClientInterface
    {
        return new Client($this->createTestHttpClientConfig($queue, $config));
    }

    public function createTestHttpClientConfig(array $queue, array $config = []): array
    {
        $mockHandler = new MockHandler($queue);
        $handlerStack = HandlerStack::create($mockHandler);

        return array_merge($config, [
            'handler' => $handlerStack,
        ]);
    }
}
