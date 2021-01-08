<?php

namespace Framework\Event;

use Psr\EventDispatcher\StoppableEventInterface;

interface EventInterface extends StoppableEventInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return mixed
     */
    public function getTarget();

    /**
     * @return array<string, mixed|null>
     */
    public function getParams(): array;

    /**
     * @param string $name
     * @return mixed
     */
    public function getParam(string $name);

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self;

    /**
     * @param array<string, mixed> $params
     * @return self
     */
    public function setParams(array $params): self;

    /**
     * @param mixed $target
     * @return self
     */
    public function setTarget($target): self;

    /**
     * @param bool $flag
     */
    public function stopPropagation(bool $flag): void;
}
