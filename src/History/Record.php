<?php

declare(strict_types=1);

namespace PHPUnit\Guzzle\TestClient\History;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

final class Record
{
    private $request;

    private $response;

    public function __construct(array $data)
    {
        $this->bindData($data);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    private function bindData(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }

        if ($this->request === null) {
            throw new \InvalidArgumentException('Record does not contain request data');
        }
        if ($this->response === null) {
            throw new \InvalidArgumentException('Record does not contain response data');
        }
    }
}
