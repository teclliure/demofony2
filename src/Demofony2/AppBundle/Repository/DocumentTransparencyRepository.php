<?php

namespace Demofony2\AppBundle\Repository;

class DocumentTransparencyRepository extends BaseRepository
{
    /**
     * Get more interesting items sorted by visits amount
     *
     * @param int $max
     *
     * @return array
     */
    public function getMoreInteresting($max = 5)
    {
        $qb = $this->createQueryBuilder('d');

        $qb->select('d, c')
            ->leftJoin('d.category', 'c')
            ->orderBy('d.visits', 'DESC')
            ->setMaxResults($max);

        return $qb->getQuery()->getResult();
    }

    /**
     * Get last items sorted by date
     *
     * @param int $max
     *
     * @return array
     */
    public function getLastItems($max = 5)
    {
        $qb = $this->createQueryBuilder('d');

        $qb->select('d, c')
            ->leftJoin('d.category', 'c')
            ->orderBy('d.updatedAt', 'DESC')
            ->setMaxResults($max);

        return $qb->getQuery()->getResult();
    }
}
