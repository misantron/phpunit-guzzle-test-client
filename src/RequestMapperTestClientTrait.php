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
    protected function createRequestMapperTestHttpClient(array $queue, callable $requestMapper): ClientInterface
    {
        return new Client($this->createTestHttpClientConfig($queue, $requestMapper));
    }

    protected function createTestHttpClientConfig(array $queue, callable $requestMapper): array
    {
        $mockHandler = new MockHandler($queue);
        $handlerStack = HandlerStack::create($mockHandler);

        $requestMapperMiddleware = Middleware::mapRequest($requestMapper);
        $handlerStack->push($requestMapperMiddleware);

        return ['handler' => $handlerStack];
    }
}
