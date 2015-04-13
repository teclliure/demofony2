<?php
namespace Demofony2\AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

class ParticipationStatisticsRepository extends EntityRepository
{
    public function getBetweenDate($startAt, $endAt, $groupBy)
    {
        $qb = $this->createQueryBuilder('s');

        if ('month' === $groupBy) {
            $qb->select('DISTINCT COUNT(s.comments) AS commentsCount, MONTH(s.day) as HIDDEN month, YEAR(s.day) as HIDDEN year, s.day ')
                ->groupBy('month')
                ->addGroupBy('year');
        }

        $qb->where('s.day >= :startAt')
            ->andWhere('s.day < :endAt')
            ->setParameter('startAt', $startAt)
            ->setParameter('endAt', $endAt);

        return $qb->getQuery()->getArrayResult();
    }

}
