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

    /**
     * @return History\Record[]
     */
    public function getRequestHistory(): array
    {
        return array_map(static function (array $row) {
            return new History\Record($row);
        }, $this->requestHistory);
    }

    public function pullRequestHistoryRecord(): History\Record
    {
        $row = array_shift($this->requestHistory);
        if ($row === null) {
            throw new \OutOfBoundsException('Request history pool is empty');
        }

        return new History\Record($row);
    }

    public function hasRequestHistory(): bool
    {
        return \count($this->requestHistory) > 0;
    }
}
