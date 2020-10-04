<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

trait RequestHistoryTestClientTrait
{
    private $requestHistory = [];

    protected function createRequestHistoryTestHttpClient(array $queue): ClientInterface
    {
        return new Client($this->createTestHttpClientConfig($queue));
    }

    protected function createTestHttpClientConfig(array $queue): array
    {
        $mockHandler = new MockHandler($queue);
        $handlerStack = HandlerStack::create($mockHandler);

        $this->requestHistory = [];
        $requestHistoryMiddleware = Middleware::history($this->requestHistory);
        $handlerStack->push($requestHistoryMiddleware);

        return ['handler' => $handlerStack];
    }

    protected function getRequestHistory(): array
    {
        return $this->requestHistory;
    }

    protected function hasRequestHistory(): bool
    {
        return \count($this->requestHistory) > 0;
    }
}
