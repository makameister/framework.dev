<?php declare(strict_types = 1);

namespace Framework\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

class EventDispatcher implements EventDispatcherInterface
{

    /**
     * @var ListenerProviderInterface
     */
    private ListenerProviderInterface $listenerProvider;

    /**
     * @param ListenerProviderInterface $listenerProvider
     */
    public function __construct(ListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * Déclenche tous les callbacks des listeners d'un événement
     *
     * @param EventInterface $event
     * @return void
     */
    public function dispatch($event): void
    {
        $listeners = $this->listenerProvider->getListenersForEvent($event);
        foreach ($listeners as ['callback' => $callback]) {
            if ($event->isPropagationStopped()) {
                break;
            }
            call_user_func($callback, $event);
        }
    }

    /**
     * @return ListenerProviderInterface
     */
    public function getListenerProvider(): ListenerProviderInterface
    {
        return $this->listenerProvider;
    }
}
