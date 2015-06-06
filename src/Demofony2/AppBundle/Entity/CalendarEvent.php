<?php

namespace Demofony2\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BladeTester\CalendarBundle\Entity\Event as BaseEvent;

/**
 * @ORM\Entity(repositoryClass="Demofony2\AppBundle\Repository\CalendarEventRepository")
 * @ORM\Table(name="demofony2_calendar_event")
 */
class CalendarEvent extends BaseEvent
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(name="entity_id", type="integer")
     */
    protected $entityId;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param int $entityId
     *
     * @return CalendarEvent
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }
}
