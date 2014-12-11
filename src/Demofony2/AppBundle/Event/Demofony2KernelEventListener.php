<?php

namespace Demofony2\AppBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Demofony2\AppBundle\Enum\Demofony2EventEnum;
use Demofony2\AppBundle\Entity\Suggestion;
use Symfony\Component\EventDispatcher\GenericEvent;

class Demofony2KernelEventListener implements EventSubscriberInterface
{

    protected $rabbitProducer;
    protected $enableRAbbitMq;

    public function __construct()
    {

    }

    public static function getSubscribedEvents()
    {
        return array(
            Demofony2EventEnum::NEW_SUGGESTION => 'onNewSuggestionEvent',

        );
    }

    public function onNewSuggestionEvent(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($entity instanceof Suggestion) {

        }
    }
}
