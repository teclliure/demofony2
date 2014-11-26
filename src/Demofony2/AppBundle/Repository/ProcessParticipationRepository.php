<?php
namespace Demofony2\AppBundle\Repository;

use Demofony2\AppBundle\Entity\Comment;
use Demofony2\AppBundle\Entity\ProcessParticipation;

class ProcessParticipationRepository extends BaseRepository
{
    public function getComments($id, $page = 1, $limit = 10, $count = false)
    {
        $qb = $this->createQueryBuilder('co');

        if ($count) {
            $qb->select('count(c.id)');
        } else {
            $qb->select('c');
        }

        $qb->from('Demofony2AppBundle:Comment', 'c')
            ->innerJoin('c.processParticipation', 'pp', 'WITH', 'pp.id = :id')
            ->where('c.lvl = :lvl')
            ->andWhere('pp.commentsModerated = :commentsModerated')
            ->orWhere('c.revised = :revised')
            ->orderBy('c.createdAt', 'DESC')
            ->setParameter('id', $id)
            ->setParameter('lvl', 0)
            ->setParameter('commentsModerated', false)
            ->setParameter('revised', true);

        if ($count) {
            return $qb->getQuery()->getSingleScalarResult();
        }

        return $this->paginateQuery($qb->getQuery(), $page, $limit);
    }

    public function getChildrenInComment(ProcessParticipation $processParticipation, Comment $comment, $page = 1, $limit = 10, $count = false)
    {
        $qb = $this->createQueryBuilder('co');
//        $qb->from('Demofony2AppBundle:Comment', 'c');

        if ($count) {
            $qb->select('COUNT(co)');
        } else {
            $qb->select('co');
        }

//            $qb->join('c.processParticipation', 'pp')
//            ->Where('c.root = :root')
////            ->andWhere('c.lvl >= :lvl')
////            ->andWhere('pp.commentsModerated = :commentsModerated')
////            ->orWhere('c.revised = :revised')
//            ->orderBy('c.lft', 'ASC')
////            ->setParameter('lvl', 1)
//            ->setParameter('root', $comment->getId())
//            ->setParameter('commentsModerated', false)
//            ->setParameter('revised', true);

        ;
        if ($count) {
            return $qb->getQuery()->getSingleScalarResult();
        }

        return $qb->getQuery()->getResult();
//        return $this->paginateQuery($qb->getQuery(), $page, $limit);
    }
}
