<?php

namespace Demofony2\AppBundle\Listener;

use Demofony2\AppBundle\Manager\StatisticsManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Demofony2\AppBundle\Entity\Vote;

/**
 * VoteSubscriber.
 */
class VoteSubscriber implements EventSubscriber
{
    protected $userCallable;
    protected $statisticsManager;

    public function __construct(callable $userCallable, StatisticsManager $sm)
    {
        $this->userCallable = $userCallable;
        $this->statisticsManager = $sm;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
       );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getEntity();

        if ($object instanceof Vote) {
            $em = $args->getEntityManager();
            $statistics = $this->statisticsManager->addVote();
            $em->persist($statistics);
        }
    }
}
