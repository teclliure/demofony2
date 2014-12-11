<?php

namespace Demofony2\AppBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Demofony2\AppBundle\Entity\ProposalAnswer;
use Demofony2\UserBundle\Entity\User;

class ProposalAnswerSubscriber implements EventSubscriber
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

        if ($object instanceof ProposalAnswer ) {

         $count = (int) $voteRepository->getVoteByUserInProposalAnswer($user->getId(), $object->getId());
               $object->setUserHasVoteThisProposalAnswer($count);

            return;
        }
    }
    private function getLoggedUser()
    {
        $callable = $this->userCallable;
        $user = $callable();

        return $user;
    }
}
