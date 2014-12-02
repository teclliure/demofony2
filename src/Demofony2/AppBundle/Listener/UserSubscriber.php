<?php

namespace Demofony2\AppBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Demofony2\AppBundle\Entity\UserAwareInterface;

class UserSubscriber implements EventSubscriber
{
    protected $userCallable;
    protected $environment;

    public function __construct(callable $userCallable, $environment)
    {
        $this->userCallable = $userCallable;
        $this->environment = $environment;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        if (php_sapi_name() === 'cli' || 'test' === $this->environment){
            return;
        }

        $object = $args->getEntity();
        if ($object instanceof UserAwareInterface) {
            $user = $this->getLoggedUser();
            $object->setAuthor($user);
        }
    }

    private function getLoggedUser()
    {
        $callable = $this->userCallable;
        $user = $callable();

        return $user;
    }
}
