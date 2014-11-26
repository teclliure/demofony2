<?php

namespace Demofony2\AppBundle\Listener;

use Demofony2\AppBundle\Entity\Comment;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class CommentSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            Events::postLoad
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $commentRepository = $em->getRepository('Demofony2AppBundle:Comment');
        $object = $args->getEntity();
        if ($object instanceof Comment && is_object($pp = $object->getProcessParticipation())) {
            $childrenCount = (int) $commentRepository->getChildrenCommentByProcessParticipation($pp->getId(), $object->getId(), null, null, true);
            $object->setChildrenCount($childrenCount);
            return;
        }
        if ($object instanceof Comment && is_object($p = $object->getProposal())) {
            $childrenCount = (int) $commentRepository->getChildrenCommentByProposal($p->getId(), $object->getId(), null, null, true);
            $object->setChildrenCount($childrenCount);
            return;
        }
    }
}