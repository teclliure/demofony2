<?php

namespace Demofony2\AppBundle\Listener;

use Demofony2\AppBundle\Manager\FileManager;
use Demofony2\UserBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Demofony2\AppBundle\Entity\UserAwareInterface;

class UserSubscriber implements EventSubscriber
{
    protected $userCallable;
    protected $environment;
    protected $fileManager;

    public function __construct(callable $userCallable, $environment, FileManager $fm)
    {
        $this->userCallable = $userCallable;
        $this->environment = $environment;
        $this->fileManager = $fm;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::postLoad,
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        //because in dev we want set user to load fixtures and in test environment user will be logged
        if (php_sapi_name() === 'cli' && 'test' !== $this->environment) {
            return;
        }

        $object = $args->getEntity();
        if ($object instanceof UserAwareInterface) {
            $user = $this->getLoggedUser();
            $object->setAuthor($user);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getEntity();

        if ($object instanceof User) {
//            $url = $this->fileManager->getUserImageUrl($object);
//            $object->setImageUrl($url);
        }
    }

    private function getLoggedUser()
    {
        $callable = $this->userCallable;
        $user = $callable();

        return $user;
    }
}
