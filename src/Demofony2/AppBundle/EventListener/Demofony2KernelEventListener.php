<?php

namespace Demofony2\AppBundle\EventListener;

use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\AppBundle\Manager\MailManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Demofony2\AppBundle\Enum\Demofony2EventEnum;
use Demofony2\AppBundle\Entity\Suggestion;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Demofony2KernelEventListener
 */
class Demofony2KernelEventListener implements EventSubscriberInterface
{
    protected $mailManager;

    /**
     * @param MailManager $mailManager mailManager
     */
    public function __construct(MailManager $mailManager)
    {
        $this->mailManager = $mailManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            Demofony2EventEnum::NEW_SUGGESTION => 'onNewSuggestionEvent',
            Demofony2EventEnum::NEW_PROPOSAL => 'onNewProposalEvent',
        );
    }

    /**
     * @param GenericEvent $event event
     */
    public function onNewSuggestionEvent(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($entity instanceof Suggestion) {
            $this->mailManager->notifyNewSuggestionCreated($entity);
        }
    }

    /**
     * @param GenericEvent $event event
     */
    public function onNewProposalEvent(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($entity instanceof Proposal) {
            $this->mailManager->notifyNewProposalCreated($entity);
        }
    }
}
