<?php declare(strict_types = 1);

namespace Framework\Event;

class Event implements EventInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var mixed
     */
    private $target;

    /**
     * @var array<string, mixed|null>
     */
    private array $params;

    /**
     * @var bool
     */
    private bool $propagationStopped = false;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return array<string, mixed|null>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getParam(string $name)
    {
        return $this->params[$name] ?? null;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param array<string, mixed> $params
     * @return self
     */
    public function setParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param mixed $target
     * @return self
     */
    public function setTarget($target): self
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @param bool $flag
     */
    public function stopPropagation(bool $flag): void
    {
        $this->propagationStopped = $flag;
    }

    /**
     * @return bool
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }
}
