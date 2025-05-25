<?php declare(strict_types=1);
namespace App\Providers;

use Careminate\EventListener\ContentLengthListener;
use Careminate\EventListener\InternalErrorListener;
use Careminate\Databases\Dbal\EventDispatcher\PostPersist;
use Careminate\Databases\Dbal\EventDispatcher\ResponseEvent;
use Careminate\Providers\Contracts\ServiceProviderInterface;
use Careminate\Databases\Dbal\EventDispatcher\EventDispatcher;

class EventServiceProvider implements ServiceProviderInterface
{
    private array $listen = [
        ResponseEvent::class => [
            //list of listeners
            InternalErrorListener::class,
            ContentLengthListener::class
        ],
        PostPersist::class => [
            //add eventlistener action
        ]
    ];

    public function __construct(private EventDispatcher $eventDispatcher)
    {
    }

    public function register(): void
    {
        // loop over each event in the listen array
        foreach ($this->listen as $eventName => $listeners) {
            // loop over each listener
            foreach (array_unique($listeners) as $listener) {
                // call eventDispatcher->addListener
                $this->eventDispatcher->addListener($eventName, new $listener());
            }
        }
    }
}

