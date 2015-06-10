<?php

namespace Demofony2\AppBundle\Listener;

use Demofony2\AppBundle\Entity\CalendarEvent;
use Demofony2\AppBundle\Entity\CitizenForum;
use Demofony2\AppBundle\Entity\CitizenInitiative;
use Demofony2\AppBundle\Manager\CalendarManager;
use Demofony2\AppBundle\Manager\StatisticsManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Demofony2\AppBundle\Entity\ProcessParticipation;
use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\UserBundle\Entity\User;
use Doctrine\ORM\UnitOfWork;

/**
 * ParticipationBaseSubscriber.
 */
class ParticipationBaseSubscriber implements EventSubscriber
{
    protected $userCallable;
    protected $statisticsManager;
    protected $calendarManager;

    public function __construct(callable $userCallable, StatisticsManager $sm, CalendarManager $calendarManager)
    {
        $this->userCallable = $userCallable;
        $this->statisticsManager = $sm;
        $this->calendarManager = $calendarManager;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::postLoad,
            Events::prePersist,
            Events::postPersist,
            Events::onFlush,
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getEntity();

        if ($object instanceof Proposal) {
            $em = $args->getEntityManager();
            $statistics = $this->statisticsManager->addProposal();
            $em->persist($statistics);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getEntity();
        $em = $args->getEntityManager();

        if (php_sapi_name() === 'cli') {
            return;
        }
        if ($object instanceof ProcessParticipation) {
            $this->calendarManager->createOrUpdateProcessParticipationEvent($object);
            $em->flush();
        }
        if ($object instanceof CitizenForum) {
            $this->calendarManager->createOrUpdateCitizenForumEvent($object);
            $em->flush();
        }
        if ($object instanceof Proposal) {
            $this->calendarManager->createOrUpdateProposalEvent($object);
            $em->flush();
        }
        if ($object instanceof CitizenInitiative) {
            $this->calendarManager->createOrUpdateCitizenInitiativeEvent($object);
            $em->flush();
        }
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() AS $entity) {
            $changes = $uow->getEntityChangeSet($entity);
            if ($entity instanceof Proposal && (isset($changes['userDraft']) || isset($changes['moderated']) || isset($changes['title']))) {
                $event = $this->calendarManager->createOrUpdateProposalEvent($entity);
                if (!$event) {
                    continue;
                }
                $this->persistCalendarEventOnFlush($event, $uow, $em);
            }
            if ($entity instanceof ProcessParticipation && (isset($changes['published']) || isset($changes['title']))) {
                $event = $this->calendarManager->createOrUpdateProcessParticipationEvent($entity);
                if (!$event) {
                    continue;
                }
                $this->persistCalendarEventOnFlush($event, $uow, $em);
            }
            if ($entity instanceof CitizenInitiative && (isset($changes['published']) || isset($changes['startAt']) || isset($changes['title']))) {
                $event = $this->calendarManager->createOrUpdateCitizenInitiativeEvent($entity);
                if (!$event) {
                    continue;
                }
                $this->persistCalendarEventOnFlush($event, $uow, $em);
            }
            if ($entity instanceof CitizenForum && (isset($changes['published']) || isset($changes['title']))) {
                $event = $this->calendarManager->createOrUpdateCitizenForumEvent($entity);
                if (!$event) {
                    continue;
                }
                $this->persistCalendarEventOnFlush($event, $uow, $em);
            }
        }

    }

    protected function persistCalendarEventOnFlush(CalendarEvent $event, UnitOfWork $uow, ObjectManager $em)
    {
        $em->persist($event);
        $md = $em->getClassMetadata('Demofony2\AppBundle\Entity\CalendarEvent');
        $uow->computeChangeSet($md, $event);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $voteRepository = $em->getRepository('Demofony2AppBundle:Vote');

        $object = $args->getEntity();
        $user = $this->getLoggedUser();

        if ($object instanceof ProcessParticipation) {
            $count = $em->getRepository('Demofony2AppBundle:Comment')->getNotModeratedCountByProcessParticipation(
                $object->getId()
            );
            $object->setCommentsNotModeratedCount($count);
        }

        if ($object instanceof ProcessParticipation && $user instanceof User) {
            $count = (int)$voteRepository->getVoteByUserInProcessParticipation(
                $user->getId(),
                $object->getId(),
                $count = true
            );
            $object->setUserAlreadyVote($count);
        }

        if ($object instanceof Proposal && $user instanceof User) {
            $count = (boolean)$voteRepository->getVoteByUserInProposal($user->getId(), $object->getId(), $count = true);
            $object->setUserAlreadyVote($count);
        }

        if ($object instanceof CitizenForum && $user instanceof User) {
            $count = (boolean)$voteRepository->getVoteByUserInCitizenForum(
                $user->getId(),
                $object->getId(),
                $count = true
            );
            $object->setUserAlreadyVote($count);
        }
    }

    private function getLoggedUser()
    {
        $callable = $this->userCallable;
        $user = $callable();

        return $user;
    }
}
