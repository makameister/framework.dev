<?php

namespace Tests\Framework\Event;

use Framework\Event\ListenerProvider;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;
use Tests\Resources\Helpers;

class ListenerProviderTest extends TestCase
{

    private ListenerProviderInterface $provider;

    use Helpers;

    protected function setUp(): void
    {
        $this->provider = new ListenerProvider();
    }

    private function makeListener(string $name, $callback, int $priority = 0)
    {
        $this->provider->attach($name, $callback, $priority);
    }

    public function testCanAttachAListener()
    {
        $this->makeListener('test.event', function () {
            echo "Hello world";
        });

        $event = $this->makeEvent('test.event');
        $this->assertCount(1, $this->provider->getListenersForEvent($event));
    }

    public function testCanAttachAnotherOneListener()
    {
        $this->makeListener('test.event', function () {
            echo "Hello world 1";
        });
        $this->makeListener('test.event', function () {
            echo "Hello world 2";
        });

        $event = $this->makeEvent('test.event');
        $this->assertCount(2, $this->provider->getListenersForEvent($event));
    }

    public function canAttachMultipleListeners()
    {
        $this->makeListener('test.event1', function () {
            echo "Hello world";
        });
        $this->makeListener('test.event2', function () {
            echo "Hello world";
        });

        $event1 = $this->makeEvent('test.event1');
        $event2 = $this->makeEvent('test.event2');
        $this->assertCount(1, $this->provider->getListenersForEvent($event1));
        $this->assertCount(1, $this->provider->getListenersForEvent($event2));
    }

    public function testListenerHasCallback()
    {
        $this->makeListener('test.event', function () {
            return "Hello world";
        }, 100);

        $event = $this->makeEvent('test.event');
        $this->assertEquals(
            "Hello world",
            call_user_func($this->provider->getListenersForEvent($event)[0]['callback'])
        );
    }

    public function testListenerHasPriority()
    {
        $this->makeListener('test.event', function () {
            echo "Hello world";
        }, 100);

        $event = $this->makeEvent('test.event');
        $this->assertEquals(100, $this->provider->getListenersForEvent($event)[0]['priority']);
    }

    public function testDetachListener()
    {
        $function = function () {
            echo "Hello world";
        };
        $this->makeListener('test.event', $function);
        $this->makeListener('test.event', $function);
        $this->makeListener('test.event', function () {
            echo "Hello world 2";
        });

        $this->provider->detach('test.event', $function);

        $event = $this->makeEvent('test.event');
        $this->assertCount(1, $this->provider->getListenersForEvent($event));
    }

    public function testClearListeners()
    {
        $function = function () {
            echo "Hello world";
        };
        $this->makeListener('test.event1', $function);
        $this->makeListener('test.event1', $function);
        $this->makeListener('test.event2', $function);

        $this->provider->clearListeners('test.event1');

        $event1 = $this->makeEvent('test.event1');
        $event2 = $this->makeEvent('test.event2');
        $this->assertCount(0, $this->provider->getListenersForEvent($event1));
        $this->assertCount(1, $this->provider->getListenersForEvent($event2));
    }
}
