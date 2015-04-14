<?php

namespace Demofony2\AppBundle\Listener;

use Demofony2\AppBundle\Entity\Comment;
use Demofony2\AppBundle\Manager\StatisticsManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class CommentSubscriber implements EventSubscriber
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

        if ($object instanceof Comment && is_object($pp = $object->getProcessParticipation())) {
            $childrenCount = $commentRepository->getChildrenCommentByProcessParticipation($pp->getId(), $object->getId(), null, null, true);
            $object->setChildrenCount($childrenCount);
        }

        if ($object instanceof Comment && is_object($p = $object->getProposal())) {
            $childrenCount = $commentRepository->getChildrenCommentByProposal($p->getId(), $object->getId(), null, null, true);
            $object->setChildrenCount($childrenCount);
        }

        if ($object instanceof Comment && is_object($cf = $object->getCitizenForum())) {
            $childrenCount = $commentRepository->getChildrenCommentByCitizenForum($cf->getId(), $object->getId(), null, null, true);
            $object->setChildrenCount($childrenCount);
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getEntity();
        $em = $args->getEntityManager();

        if ($object instanceof Comment) {
             $statistics = $this->statisticsManager->addComment();
             $em->persist($statistics);
        }

        if ($object instanceof Comment && is_object($pp = $object->getProcessParticipation())) {
            $object->setModerated($pp->getCommentsModerated());

            return;
        }
        if ($object instanceof Comment && is_object($p = $object->getProposal())) {
            $object->setModerated($p->getCommentsModerated());

            return;
        }
        if ($object instanceof Comment && is_object($p = $object->getCitizenForum())) {
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
