<?php

namespace Demofony2\AppBundle\Repository;

use BladeTester\CalendarBundle\Entity\EventCategory;
use BladeTester\CalendarBundle\Repository\EventRepository;

/**
 * CalendarEventRepository
 */
class CalendarEventRepository extends EventRepository
{

    /**
     * @param EventCategory $eventCategory
     * @param               $entityId
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByCategoryAndEntityId(EventCategory $eventCategory, $entityId)
    {
        return $this->createQueryBuilder('ce')
            ->select('ce')
            ->join('ce.category', 'c', 'WITH', 'c = :category')
            ->where('ce.entityId = :entityId')
            ->setParameter('category', $eventCategory)
            ->setParameter('entityId', $entityId)
            ->getQuery()->getOneOrNullResult();
    }
}
