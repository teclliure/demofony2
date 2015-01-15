<?php

namespace Demofony2\AppBundle\EventListener;

use Demofony2\AppBundle\Manager\MailManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Demofony2\AppBundle\Enum\Demofony2EventEnum;
use Demofony2\AppBundle\Entity\Suggestion;
use Symfony\Component\EventDispatcher\GenericEvent;

class Demofony2KernelEventListener implements EventSubscriberInterface
{
    protected $mailManager;

    public function __construct(MailManager $mailManager)
    {
        $this->mailManager = $mailManager;
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
