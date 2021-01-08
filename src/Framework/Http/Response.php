<?php declare(strict_types = 1);

namespace Framework\Http;

class Response extends \GuzzleHttp\Psr7\Response
{
    /**
     * Response constructor.
     * @param int $status
     * @param array<string, string> $headers
     * @param string $body
     * @param string $version
     * @param null $reason
     */
    public function __construct(
        int $status = 200,
        array $headers = [],
        string $body = null,
        string $version = '1.1',
        $reason = null
    ) {
        parent::__construct($status, $headers, $body, $version, $reason);
    }
}
