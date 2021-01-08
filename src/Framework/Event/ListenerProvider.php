<?php declare(strict_types = 1);

namespace Framework\Event;

use Psr\EventDispatcher\ListenerProviderInterface;

class ListenerProvider implements ListenerProviderInterface
{

    /**
     * Liste des listeners
     *
     * @var array<string, array<string, mixed>>
     */
    private array $listeners = [];

    /**
     * Retoune la liste des listeners d'un événément triée par ordre de priorité
     *
     * @param EventInterface $event
     * @return iterable<array>|array<null>
     */
    public function getListenersForEvent($event): iterable
    {
        if (array_key_exists($event->getName(), $this->listeners)) {
            $listeners = $this->listeners[$event->getName()];
            usort($listeners, function ($listenerA, $listenerB) {
                return $listenerB['priority'] - $listenerA['priority'];
            });
            return $listeners;
        }
        return [];
    }

    /**
     * Ajoute une listener à un événément
     *
     * @param string $event
     * @param callable|string|array<string, string> $callback
     * @param int $priority
     */
    public function attach(string $event, $callback, int $priority = 0): void
    {
        $this->listeners[$event][] = [
            'callback' => $callback,
            'priority' => $priority
        ];
    }

    /**
     * Retire un listener d'un événément via son callback
     *
     * @param string $event
     * @param callable|string|array<string, string> $callback
     */
    public function detach(string $event, $callback): void
    {
        $this->listeners[$event] = array_filter($this->listeners[$event], function ($listener) use ($callback) {
            return $listener['callback'] !== $callback;
        });
    }

    /**
     * Retire tous les listeners d'un événément
     *
     * @param string $event
     */
    public function clearListeners(string $event): void
    {
        $this->listeners[$event] = [];
    }
}
