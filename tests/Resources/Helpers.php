<?php

namespace Tests\Resources;

use Framework\Event\Event;
use Framework\Event\EventInterface;
use Framework\Http\Response;
use Prophecy\Argument;
use Prophecy\Prophet;
use Psr\Http\Server\RequestHandlerInterface;

trait Helpers
{
    public function makeRequestHandler()
    {
        $prophet = new Prophet;
        $requestHandler = $prophet->prophesize(RequestHandlerInterface::class);
        $requestHandler->handle(Argument::any())->willReturn(new Response());
        return $requestHandler;
    }

    private function makeEvent(string $name, $target = null, array $params = []): EventInterface
    {
        return (new Event())
            ->setName($name)
            ->setTarget($target)
            ->setParams($params);
    }
}
