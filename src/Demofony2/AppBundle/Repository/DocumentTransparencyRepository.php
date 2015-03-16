<?php
namespace Demofony2\AppBundle\Repository;

class DocumentTransparencyRepository extends BaseRepository
{
    public function getMoreInteresting($max = 5)
    {
        $qb = $this->createQueryBuilder('d');

        $qb->select('d')
            ->orderBy('d.visits', 'DESC')
            ->setMaxResults($max);

        return $qb->getQuery()->getArrayResult();
    }
}
