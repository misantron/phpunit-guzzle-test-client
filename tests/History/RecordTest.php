<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient\Tests\History;

use PHPUnit\Framework\TestCase;
use PHPUnit\Guzzle\TestClient\History\Record;

class RecordTest extends TestCase
{
    public function testConstructWithoutRequestData(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Record does not contain request data');

        new Record([
            'response' => [
                'response_data',
            ],
        ]);
    }

    public function testConstructWithoutResponseData(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Record does not contain response data');

        new Record([
            'request' => [
                'request_data',
            ],
        ]);
    }
}
