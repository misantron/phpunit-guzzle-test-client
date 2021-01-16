<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient\Example;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use PHPUnit\Guzzle\TestClient\RequestHistoryTestClientTrait;

class RequestHistoryTestClientTestExample extends TestCase
{
    use RequestHistoryTestClientTrait;

    public function testMethodOne(): void
    {
        $queue = [
            new Response(200, [], '{"ok":true}'),
        ];

        $service = new ServiceExample(
            $this->createRequestHistoryTestHttpClient($queue)
        );
        $result = $service->methodOne();

        self::assertSame(200, $result->getStatusCode());
        self::assertSame('{"ok":true}', $result->getBody()->getContents());

        self::assertTrue($this->hasRequestHistory());
        self::assertCount(1, $this->getRequestHistory());

        $record = $this->pullRequestHistoryRecord();

        self::assertSame('GET', $record->getRequest()->getMethod());
        self::assertSame('/path-one', $record->getRequest()->getUri()->getPath());
    }

    public function testMethodTwo(): void
    {
        $queue = [
            new Response(200, [], '{"ok":true}'),
        ];
        $config = $this->createRequestHistoryTestHttpClientConfig($queue);

        $service = new ServiceExample(
            new Client($config)
        );
        $result = $service->methodTwo(['foo' => 'bar']);

        self::assertSame(200, $result->getStatusCode());
        self::assertSame('{"ok":true}', $result->getBody()->getContents());

        self::assertTrue($this->hasRequestHistory());
        self::assertCount(1, $this->getRequestHistory());

        $record = $this->pullRequestHistoryRecord();

        self::assertSame('POST', $record->getRequest()->getMethod());
        self::assertSame('/path-two', $record->getRequest()->getUri()->getPath());
        self::assertSame('{"foo":"bar"}', $record->getRequest()->getBody()->getContents());
    }
}
