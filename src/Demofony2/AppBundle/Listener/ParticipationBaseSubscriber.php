<?php

namespace Demofony2\AppBundle\Listener;

use Demofony2\AppBundle\Entity\CitizenForum;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Demofony2\AppBundle\Entity\ProcessParticipation;
use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\UserBundle\Entity\User;

/**
 * ParticipationBaseSubscriber
 */
class ParticipationBaseSubscriber implements EventSubscriber
{
    protected $userCallable;

    public function __construct(callable $userCallable)
    {
        $this->userCallable = $userCallable;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::postLoad,
       );
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
            $count = $em->getRepository('Demofony2AppBundle:Comment')->getNotModeratedCountByProcessParticipation($object->getId());
            $object->setCommentsNotModeratedCount($count);
        }

        if ($object instanceof ProcessParticipation && $user instanceof User) {
            $count = (int) $voteRepository->getVoteByUserInProcessParticipation($user->getId(), $object->getId(), $count = true);
            $object->setUserAlreadyVote($count);
        }

        if ($object instanceof Proposal && $user instanceof User) {
            $count = (boolean) $voteRepository->getVoteByUserInProposal($user->getId(), $object->getId(), $count = true);
            $object->setUserAlreadyVote($count);
        }

        if ($object instanceof CitizenForum && $user instanceof User) {
            $count = (boolean) $voteRepository->getVoteByUserInCitizenForum($user->getId(), $object->getId(), $count = true);
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
