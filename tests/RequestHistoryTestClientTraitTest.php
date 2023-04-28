<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient\Tests;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\TestCase;
use PHPUnit\Guzzle\TestClient\RequestHistoryTestClientTrait;

class RequestHistoryTestClientTraitTest extends TestCase
{
    /**
     * @dataProvider clientConfigDataProvider
     */
    public function testCreateRequestHistoryTestHttpClientConfig(array $queue, array $config, callable $assertion): void
    {
        $test = new class() {
            use RequestHistoryTestClientTrait;
        };
        $actual = $test->createRequestHistoryTestHttpClientConfig($queue, $config);

        $assertion($actual);
    }

    public static function clientConfigDataProvider(): array
    {
        return [
            '#1' => [
                [],
                [],
                function (array $actual) {
                    self::assertArrayNotHasKey('base_uri', $actual);
                    self::assertArrayHasKey('handler', $actual);
                    self::assertInstanceOf(HandlerStack::class, $actual['handler']);
                    self::assertTrue($actual['handler']->hasHandler());
                },
            ],
            '#2' => [
                [],
                ['base_uri' => 'https://example.com'],
                function (array $actual) {
                    self::assertArrayHasKey('base_uri', $actual);
                    self::assertSame('https://example.com', $actual['base_uri']);
                    self::assertArrayHasKey('handler', $actual);
                    self::assertInstanceOf(HandlerStack::class, $actual['handler']);
                },
            ],
            '#3' => [
                [
                    new Response(),
                ],
                ['base_uri' => 'https://example.com'],
                function (array $actual) {
                    self::assertArrayHasKey('base_uri', $actual);
                    self::assertSame('https://example.com', $actual['base_uri']);
                    self::assertArrayHasKey('handler', $actual);
                    self::assertInstanceOf(HandlerStack::class, $actual['handler']);
                },
            ],
        ];
    }

    public function testCreateRequestHistoryTestHttpClient(): void
    {
        $queue = [
            new Response(200, [], 'test'),
        ];

        $test = new class() {
            use RequestHistoryTestClientTrait;
        };
        $client = $test->createRequestHistoryTestHttpClient($queue);

        self::assertFalse($test->hasRequestHistory());
        self::assertCount(0, $test->getRequestHistory());

        $response = $client->request('GET', '/path');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('test', $response->getBody()->getContents());

        self::assertTrue($test->hasRequestHistory());
        self::assertCount(1, $test->getRequestHistory());
    }

    public function testPullRequestHistoryRecord(): void
    {
        $queue = [
            new Response(200, [], 'test'),
            new Response(404, [], 'Not found'),
        ];
        $config = [RequestOptions::HTTP_ERRORS => false];

        $test = new class() {
            use RequestHistoryTestClientTrait;
        };
        $client = $test->createRequestHistoryTestHttpClient($queue, $config);
        $client->request('GET', '/path/test');
        $client->request('POST', '/path/not/found');

        self::assertTrue($test->hasRequestHistory());
        self::assertCount(2, $test->getRequestHistory());

        $record = $test->pullRequestHistoryRecord();

        self::assertCount(1, $test->getRequestHistory());
        self::assertSame('GET', $record->getRequest()->getMethod());

        $record = $test->pullRequestHistoryRecord();

        self::assertFalse($test->hasRequestHistory());
        self::assertSame('POST', $record->getRequest()->getMethod());
        self::assertSame('Not found', $record->getResponse()->getBody()->getContents());
    }

    public function testPullRequestHistoryRecordWithEmptyPool(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Request history pool is empty');

        $test = new class() {
            use RequestHistoryTestClientTrait;
        };
        $test->createRequestHistoryTestHttpClient([]);

        $test->pullRequestHistoryRecord();
    }
}
