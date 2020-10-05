<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient\Tests;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use PHPUnit\Guzzle\TestClient\RequestHistoryTestClientTrait;

class RequestHistoryTestClientTraitTest extends TestCase
{
    /**
     * @param array $queue
     * @param array $config
     * @param callable $assertion
     *
     * @dataProvider clientConfigDataProvider
     */
    public function testCreateTestHttpClientConfig(array $queue, array $config, callable $assertion): void
    {
        $test = new class () {
            use RequestHistoryTestClientTrait;
        };
        $actual = $test->createTestHttpClientConfig($queue, $config);

        $assertion($actual);
    }

    public function clientConfigDataProvider(): array
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

        $test = new class () {
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
}