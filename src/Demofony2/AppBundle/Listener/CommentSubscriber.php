<?php

namespace Demofony2\AppBundle\Listener;

use Demofony2\AppBundle\Entity\Comment;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class CommentSubscriber implements EventSubscriber
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
            Events::prePersist,
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

        if ($object instanceof Comment) {
            $likesCount = (int) $commentRepository->getLikesCount($object->getId());
            $unlikesCount = (int) $commentRepository->getUnLikesCount($object->getId());
            $object->setLikesCount($likesCount);
            $object->setUnlikesCount($unlikesCount);
        }

        if ($object instanceof Comment && is_object($user = $this->getLoggedUser())) {
            $like = (boolean) $commentRepository->getLikesCount($object->getId(), $user->getId());
            $unlike = (boolean) $commentRepository->getUnLikesCount($object->getId(), $user->getId());
            $object->setUserAlreadyLike($like);
            $object->setUserAlreadyUnlike($unlike);
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getEntity();

        if ($object instanceof Comment && is_object($pp = $object->getProcessParticipation())) {
            $object->setModerated($pp->getCommentsModerated());

            return;
        }
        if ($object instanceof Comment && is_object($p = $object->getProposal())) {
            $object->setModerated($p->getCommentsModerated());

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
