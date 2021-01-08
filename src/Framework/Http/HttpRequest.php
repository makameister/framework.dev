<?php declare(strict_types = 1);

namespace Framework\Http;

use GuzzleHttp\Psr7\ServerRequest;

class HttpRequest extends ServerRequest
{
    /**
     * HttpRequest constructor.
     * @param string $method
     * @param string $uri
     * @param array<string, string|null> $headers
     * @param string|null $body
     * @param string $version
     * @param array<string, string|null> $serverParams
     */
    public function __construct(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        $version = '1.1',
        array $serverParams = []
    ) {
        parent::__construct($method, $uri, $headers, $body, $version, $serverParams);
    }
}
