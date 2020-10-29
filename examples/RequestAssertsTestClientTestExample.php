<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient\Example;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use PHPUnit\Guzzle\TestClient\RequestAssertsTestClientTrait;
use Psr\Http\Message\RequestInterface;

class RequestAssertsTestClientTestExample extends TestCase
{
    use RequestAssertsTestClientTrait;

    public function testMethodOne(): void
    {
        $queue = [
            new Response(200, [], '{"ok":true}'),
        ];
        $assertsCallback = function (RequestInterface $request) {
            self::assertSame('GET', $request->getMethod());
            self::assertSame('/path-one', $request->getUri()->getPath());

            return $request;
        };

        $service = new ServiceExample(
            $this->createRequestAssertsTestHttpClient($queue, $assertsCallback)
        );
        $result = $service->methodOne();

        self::assertSame(200, $result->getStatusCode());
        self::assertSame('{"ok":true}', $result->getBody()->getContents());
    }

    public function testMethodTwo(): void
    {
        $queue = [
            new Response(200, [], '{"ok":true}'),
        ];
        $assertsCallback = function (RequestInterface $request) {
            self::assertSame('POST', $request->getMethod());
            self::assertSame('/path-two', $request->getUri()->getPath());
            self::assertSame('{"foo":"bar"}', $request->getBody()->getContents());

            return $request;
        };
        $config = $this->createTestHttpClientConfig($queue, $assertsCallback);

        $service = new ServiceExample(
            new Client($config)
        );
        $result = $service->methodTwo(['foo' => 'bar']);

        self::assertSame(200, $result->getStatusCode());
        self::assertSame('{"ok":true}', $result->getBody()->getContents());
    }
}
