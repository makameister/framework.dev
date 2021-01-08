<?php

namespace Tests\Resources\Controller;

use Framework\Http\Response;
use Psr\Http\Message\ResponseInterface;

class TestController
{

    public function __invoke(): string
    {
        return "Hello world";
    }

    public function stringResponse(): string
    {
        return "Hello world 2";
    }

    public function testResponse(): ResponseInterface
    {
        return new Response(200, [], "Hello world 3");
    }

    public function badResponse(): array
    {
        return ["Hello", "World"];
    }

}