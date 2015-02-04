<?php

namespace Demofony2\AppBundle\Listener;

use Demofony2\AppBundle\Entity\Suggestion;
use Demofony2\AppBundle\Enum\Demofony2EventEnum;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class SuggestionSubscriber implements EventSubscriber
{
    protected $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::postPersist,
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getEntity();

        if ('cli' === php_sapi_name()) {
            return;
        }

        if ($object instanceof Suggestion) {
            $event = new GenericEvent($object);
            $this->dispatchEvent(Demofony2EventEnum::NEW_SUGGESTION, $event);
        }
    }

    private function dispatchEvent($demofony2Event, GenericEvent $event)
    {
        $this->eventDispatcher->dispatch($demofony2Event, $event);
    }
}
