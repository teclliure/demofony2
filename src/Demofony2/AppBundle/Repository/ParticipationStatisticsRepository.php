<?php

namespace Demofony2\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ParticipationStatisticsRepository extends EntityRepository
{
    /**
     * @param $startAt
     * @param $endAt
     * @param $groupBy
     *
     * @return array
     */
    public function getBetweenDate($startAt, $endAt, $groupBy)
    {
        $qb = $this->createQueryBuilder('s');

        if ('year' === $groupBy) {
            $qb->select(
                'SUM(s.comments) AS comments, SUM(s.votes) as votes, SUM(s.proposals) as proposals, (SUM(s.comments) + SUM(s.votes) + SUM(s.proposals)) as total , YEAR(s.day) as date'
            )
                ->groupBy('date');
        } elseif ('month' === $groupBy) {
            $qb->select(
                "SUM(s.comments) AS comments, SUM(s.votes) as votes, SUM(s.proposals) as proposals, (SUM(s.comments) + SUM(s.votes) + SUM(s.proposals)) as total , MONTH(s.day) as HIDDEN month, YEAR(s.day) as HIDDEN year, DATE_FORMAT(s.day , '%Y%m') as date"
            )
                ->groupBy('month')
                ->addGroupBy('year');
        } elseif ('week' === $groupBy) {
            $qb->select(
                'SUM(s.comments) AS comments, SUM(s.votes) as votes, SUM(s.proposals) as proposals, (SUM(s.comments) + SUM(s.votes) + SUM(s.proposals)) as total , WEEK(s.day) as date, MONTH(s.day) as HIDDEN month, YEAR(s.day) as HIDDEN year '
            )
                ->groupBy('date')
                ->addGroupBy('month')
                ->addGroupBy('year');
        } elseif ('day' === $groupBy) {
            $qb->select(
                "SUM(s.comments) AS comments, SUM(s.votes) as votes, SUM(s.proposals) as proposals, (SUM(s.comments) + SUM(s.votes) + SUM(s.proposals)) as total , DATE_FORMAT(s.day , '%Y%m%d') as date")
                ->groupBy('s.day');
        }
        $qb->where('s.day >= :startAt')
            ->andWhere('s.day <= :endAt')
            ->setParameter('startAt', $startAt)
            ->setParameter('endAt', $endAt);

        return $qb->getQuery()->getArrayResult();
    }
}
