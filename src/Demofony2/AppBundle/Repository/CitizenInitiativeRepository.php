<?php

namespace Demofony2\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class CitizenForumRepository.
 *
 * @author   David Romaní <david@flux.cat>
 */
class CitizenInitiativeRepository extends EntityRepository
{
    public function getOpenQueryBuilder()
    {
        $qb = $this->createQueryBuilder('ci')
            ->select('ci')
            ->where('ci.startAt <= :today')
            ->andWhere('ci.finishAt >= :today')
            ->setParameter('today', new \DateTime('TODAY'))
        ;

        return $qb;
    }

    public function getClosedQueryBuilder()
    {
        $qb = $this->createQueryBuilder('ci')
            ->select('ci')
            ->where('ci.startAt <= :today')
            ->andWhere('ci.finishAt <= :today')
            ->setParameter('today', new \DateTime('TODAY'))
        ;

        return $qb;
    }
}
