<?php
namespace Demofony2\AppBundle\Repository;

class DocumentTransparencyRepository extends BaseRepository
{
    public function getMoreInteresting($max = 5)
    {
        $qb = $this->createQueryBuilder('d');

        $qb->select('d, c')
            ->leftJoin('d.category', 'c')
            ->orderBy('d.visits', 'DESC')
            ->setMaxResults($max);

        return $qb->getQuery()->getResult();
    }
}
