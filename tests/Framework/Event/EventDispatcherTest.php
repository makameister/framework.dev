<?php

namespace Tests\Framework\Event;

use Framework\Event\EventDispatcher;
use Framework\Event\EventInterface;
use Framework\Event\ListenerProvider;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tests\Resources\Helpers;

class EventDispatcherTest extends TestCase
{

    private EventDispatcherInterface $manager;

    use Helpers;

    protected function setUp(): void
    {
        $this->manager = new EventDispatcher(new ListenerProvider());
    }

    public function __invoke()
    {
        echo "Event";
    }

    public function event()
    {
        echo "Event";
    }

    public function attachListener(string $name, $callback, int $priority = 0)
    {
        $this->manager->getListenerProvider()->attach($name, $callback, $priority);
    }

    public function testCanDispatchEvent()
    {
        $this->attachListener('test.event', function () {
            echo "Event";
        });

        $event = $this->makeEvent('test.event');

        $this->manager->dispatch($event);
        $this->expectOutputString("Event");
    }

    public function testCanDispatchMutipleEvents()
    {
        $this->attachListener('test.event', function () {
            echo "Event1";
        });
        $this->attachListener('test.event', function () {
            echo "Event2";
        });

        $event = $this->makeEvent('test.event');

        $this->manager->dispatch($event);
        $this->expectOutputString("Event1Event2");
    }

    public function testDispatchTheRightEvent()
    {
        $this->attachListener('test.event1', function () {
            echo "Event1";
        });
        $this->attachListener('test.event2', function () {
            echo "Event2";
        });

        $event = $this->makeEvent('test.event1');

        $this->manager->dispatch($event);
        $this->expectOutputString("Event1");
    }

    public function testDispatchInvokableCallback()
    {
        $this->attachListener('test.event', $this);

        $event = $this->makeEvent('test.event');
        $this->manager->dispatch($event);
        $this->expectOutputString("Event");
    }

    public function testDispatchArrayCallback()
    {
        $this->attachListener('test.event', [$this, 'event']);

        $event = $this->makeEvent('test.event');
        $this->manager->dispatch($event);
        $this->expectOutputString("Event");
    }

    public function testDispatchWithPriority()
    {
        $this->attachListener('test.event', function () {
            echo "Event1";
        }, 100);
        $this->attachListener('test.event', function () {
            echo "Event2";
        }, 10);
        $this->attachListener('test.event', function () {
            echo "Event3";
        }, 1000);

        $event = $this->makeEvent('test.event');
        $this->manager->dispatch($event);
        $this->expectOutputString("Event3Event1Event2");
    }

    public function testCanDispatchWithEvent()
    {
        $this->attachListener('test.event', function (EventInterface $event) {
            echo $event->getTarget();
        });

        $event = $this->makeEvent('test.event', 'Event');
        $this->manager->dispatch($event);
        $this->expectOutputString("Event");
    }

    public function testCanDispatchWithEventParams()
    {
        $this->attachListener('test.event', function (EventInterface $event) {
            echo implode(" ", $event->getParams());
        });

        $event = $this->makeEvent('test.event', 'Event', ['param1' => 'Hello', 'param2' => 'world']);
        $this->manager->dispatch($event);
        $this->expectOutputString("Hello world");
    }

    public function testCanDispatchWithEventParam()
    {
        $this->attachListener('test.event', function (EventInterface $event) {
            echo $event->getParam('param2');
        });

        $event = $this->makeEvent('test.event', 'Event', ['param1' => 'Hello', 'param2' => 'world']);
        $this->manager->dispatch($event);
        $this->expectOutputString("world");
    }

    public function testCanStopPropagation()
    {
        $this->attachListener('test.event', function (EventInterface $event) {
            echo "Hello";
            $event->stopPropagation(true);
        }, 1000);
        $this->attachListener('test.event', function (EventInterface $event) {
            echo "World";
        });

        $event = $this->makeEvent('test.event');
        $this->manager->dispatch($event);
        $this->expectOutputString("Hello");
    }

    public function testWithNoListeners()
    {
        $event = $this->makeEvent('test.event');
        $this->manager->dispatch($event);
        $this->expectOutputString("");
    }
}
