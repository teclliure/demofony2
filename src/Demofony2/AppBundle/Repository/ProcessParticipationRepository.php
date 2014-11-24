<?php
namespace Demofony2\AppBundle\Repository;

class ProcessParticipationRepository extends BaseRepository
{
    public function getComments($id, $page = 1, $limit = 10)
    {
        $qb = $this->createQueryBuilder('co')
            ->from('Demofony2AppBundle:Comment', 'c')
            ->select('c')
            ->innerJoin('c.processParticipation', 'pp', 'WITH', 'pp.id = :id')
            ->andWhere('pp.commentsModerated = :commentsModerated')
            ->orWhere('c.revised = :revised')
            ->orderBy('c.createdAt', 'DESC')
            ->setParameter('id', $id)
            ->setParameter('commentsModerated', false)
            ->setParameter('revised', true)
        ;

        return $this->paginateQuery($qb->getQuery(), $page, $limit);
    }
}
