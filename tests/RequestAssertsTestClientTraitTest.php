<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient\Tests;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\TestCase;
use PHPUnit\Guzzle\TestClient\RequestAssertsTestClientTrait;

class RequestAssertsTestClientTraitTest extends TestCase
{
    /**
     * @param array $queue
     * @param callable $assertsCallback
     * @param array $config
     * @param callable $assertion
     *
     * @dataProvider clientConfigDataProvider
     */
    public function testCreateTestHttpClientConfig(
        array $queue,
        callable $assertsCallback,
        array $config,
        callable $assertion
    ): void {
        $test = new class () {
            use RequestAssertsTestClientTrait;
        };
        $actual = $test->createTestHttpClientConfig($queue, $assertsCallback, $config);

        $assertion($actual);
    }

    public function clientConfigDataProvider(): array
    {
        return [
            '#1' => [
                [],
                static function () {
                },
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
                static function () {
                },
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
                function (Request $request) {
                    self::assertSame('GET', $request->getMethod());

                    return $request;
                },
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

    public function testCreateRequestAssertsTestHttpClient(): void
    {
        $queue = [
            new Response(200, [], 'test'),
        ];
        $assertsCallback = function (Request $request) {
            self::assertSame('GET', $request->getMethod());
            self::assertSame(['text/plain'], $request->getHeader('Content-Type'));
            self::assertSame('https://example.com/path', (string) $request->getUri());

            return $request;
        };
        $config = ['base_uri' => 'https://example.com'];

        $test = new class () {
            use RequestAssertsTestClientTrait;
        };
        $client = $test->createRequestAssertsTestHttpClient($queue, $assertsCallback, $config);
        $response = $client->request('GET', '/path', [
            RequestOptions::HEADERS => ['Content-Type' => 'text/plain'],
        ]);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('test', $response->getBody()->getContents());
    }
}
