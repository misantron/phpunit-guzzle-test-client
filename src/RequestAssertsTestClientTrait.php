<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

trait RequestAssertsTestClientTrait
{
    public function createRequestAssertsTestHttpClient(
        array $queue,
        callable $assertsCallback,
        array $config = []
    ): ClientInterface {
        return new Client($this->createRequestAssertsTestHttpClientConfig($queue, $assertsCallback, $config));
    }

    public function createRequestAssertsTestHttpClientConfig(
        array $queue,
        callable $assertsCallback,
        array $config = []
    ): array {
        $mockHandler = new MockHandler($queue);
        $handlerStack = HandlerStack::create($mockHandler);

        $requestMapperMiddleware = Middleware::mapRequest($assertsCallback);
        $handlerStack->push($requestMapperMiddleware);

        return array_merge($config, [
            'handler' => $handlerStack,
        ]);
    }
}
