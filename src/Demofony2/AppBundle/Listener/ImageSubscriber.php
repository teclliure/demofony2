<?php

namespace Demofony2\AppBundle\Listener;

use Demofony2\AppBundle\Entity\Image;
use Demofony2\AppBundle\Manager\FileManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class ImageSubscriber implements EventSubscriber
{
    protected $fileManager;

    public function __construct(FileManager $fm)
    {
        $this->fileManager = $fm;
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
        $object = $args->getEntity();

        if ($object instanceof Image) {
            $url = $this->fileManager->getImageUrl($object);
            $object->setSmall($url);

            $url = $this->fileManager->getImageUrl($object, 'big');
            $object->setBig($url);
        }
    }
}
