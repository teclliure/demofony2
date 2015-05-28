<?php

namespace Demofony2\AppBundle\Listener;

use Demofony2\AppBundle\Enum\Demofony2EventEnum;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Demofony2\AppBundle\Entity\Proposal;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * ProposalSubscriber.
 */
class ProposalSubscriber implements EventSubscriber
{
    protected $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        if ('cli' === php_sapi_name()) {
            return;
        }

        $object = $args->getEntity();

        if ($object instanceof Proposal && false === $object->getUserDraft()) {
            $event = new GenericEvent($object);
            $this->dispatchEvent(Demofony2EventEnum::NEW_PROPOSAL, $event);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        if ('cli' === php_sapi_name()) {
            return;
        }

        $object = $args->getEntity();
        $changes = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($object);

        if ($object instanceof Proposal && isset($changes['userDraft']) && false === $changes['userDraft'][1]) {
            $event = new GenericEvent($object);
            $this->dispatchEvent(Demofony2EventEnum::NEW_PROPOSAL, $event);
        }
    }

    private function dispatchEvent($demofony2Event, GenericEvent $event)
    {
        $this->eventDispatcher->dispatch($demofony2Event, $event);
    }
}
