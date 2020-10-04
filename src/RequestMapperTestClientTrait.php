<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

trait RequestMapperTestClientTrait
{
    protected function createRequestMapperTestHttpClient(
        array $queue,
        callable $requestMapper,
        array $config = []
    ): ClientInterface {
        return new Client($this->createTestHttpClientConfig($queue, $requestMapper, $config));
    }

    protected function createTestHttpClientConfig(array $queue, callable $requestMapper, array $config = []): array
    {
        $mockHandler = new MockHandler($queue);
        $handlerStack = HandlerStack::create($mockHandler);

        $requestMapperMiddleware = Middleware::mapRequest($requestMapper);
        $handlerStack->push($requestMapperMiddleware);

        return array_merge($config, ['handler' => $handlerStack]);
    }
}
