<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient\Example;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use PHPUnit\Guzzle\TestClient\TestClientTrait;

class TestClientTestExample extends TestCase
{
    use TestClientTrait;

    public function testMethodOne(): void
    {
        $queue = [
            new Response(200, [], '{"ok":true}'),
        ];
        $config = [
            'base_uri' => 'https://domain.test',
        ];

        $service = new ServiceExample(
            $this->createTestHttpClient($queue, $config)
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
        $config = [
            'base_uri' => 'https://domain.test',
        ];
        $clientConfig = $this->createTestHttpClientConfig($queue, $config);

        $service = new ServiceExample(
            new Client($clientConfig)
        );
        $result = $service->methodTwo([
            'foo' => 'bar',
        ]);

        self::assertSame(200, $result->getStatusCode());
        self::assertSame('{"ok":true}', $result->getBody()->getContents());
    }
}
