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

    public function createRequestHistoryTestHttpClient(array $queue, array $config = []): ClientInterface
    {
        return new Client($this->createTestHttpClientConfig($queue, $config));
    }

    public function createTestHttpClientConfig(array $queue, array $config = []): array
    {
        $mockHandler = new MockHandler($queue);
        $handlerStack = HandlerStack::create($mockHandler);

        $this->requestHistory = [];
        $requestHistoryMiddleware = Middleware::history($this->requestHistory);
        $handlerStack->push($requestHistoryMiddleware);

        return array_merge($config, ['handler' => $handlerStack]);
    }

    public function getRequestHistory(): array
    {
        return $this->requestHistory;
    }

    public function hasRequestHistory(): bool
    {
        return \count($this->requestHistory) > 0;
    }
}
