<?php
namespace Demofony2\AppBundle\Repository;

use Demofony2\AppBundle\Entity\Comment;
use Demofony2\AppBundle\Entity\ProcessParticipation;

class CommentRepository extends BaseRepository
{
    public function getChildrenCommentByProcessParticipation($processParticipationId, $commentId, $page = 1, $limit = 10, $count = false)
    {
        $qb = $this->createQueryBuilder('c');

        if ($count) {
            $qb->select('COUNT(c.id)');
        } else {
            $qb->select('c');
        }

            $qb
            ->innerJoin('c.processParticipation', 'pp', 'WITH', 'pp.id = :id' )
            ->Where('c.root = :root')
            ->andWhere('c.lvl >= :lvl')
            ->andWhere('pp.commentsModerated = :commentsModerated OR c.revised = :revised')
            ->andWhere('c.moderated = :commentsModerated')
            ->setParameter('id', $processParticipationId)
            ->setParameter('lvl', 1)
            ->setParameter('root', $commentId)
            ->setParameter('commentsModerated', false)
            ->setParameter('revised', true);

        if ($count) {
            return $qb->getQuery()->getSingleScalarResult();
        }
             $qb->orderBy('c.lft', 'ASC');

        return $this->paginateQuery($qb->getQuery(), $page, $limit);
    }

    public function getCommentsByProcessParticipation($id, $page = 1, $limit = 10, $count = false)
    {
        $qb = $this->createQueryBuilder('c');

        if ($count) {
            $qb->select('COUNT(c.id)');
        } else {
            $qb->select('c');
        }

            $qb->innerJoin('c.processParticipation', 'pp', 'WITH', 'pp.id = :id')
            ->where('c.lvl = :lvl')
            ->andWhere('pp.commentsModerated = :commentsModerated OR c.revised = :revised')
            ->andWhere('c.moderated = :commentsModerated')
            ->setParameter('id', $id)
            ->setParameter('lvl', 0)
            ->setParameter('commentsModerated', false)
            ->setParameter('revised', true)
        ;

        if ($count) {
            return $qb->getQuery()->getSingleScalarResult();
        }

        $qb->orderBy('c.createdAt', 'DESC');

        return $this->paginateQuery($qb->getQuery(), $page, $limit);
    }


    public function getCommentsByProposal($id, $page = 1, $limit = 10, $count = false)
    {
        $qb = $this->createQueryBuilder('c');

        if ($count) {
            $qb->select('COUNT(c.id)');
        } else {
            $qb->select('c');
        }

        $qb->innerJoin('c.proposal', 'p', 'WITH', 'p.id = :id')
            ->where('c.lvl = :lvl')
            ->andWhere('p.commentsModerated = :commentsModerated OR c.revised = :revised')
            ->andWhere('c.moderated = :commentsModerated')
            ->setParameter('id', $id)
            ->setParameter('lvl', 0)
            ->setParameter('commentsModerated', false)
            ->setParameter('revised', true)
        ;

        if ($count) {
            return $qb->getQuery()->getSingleScalarResult();
        }

        $qb->orderBy('c.createdAt', 'DESC');

        return $this->paginateQuery($qb->getQuery(), $page, $limit);
    }
}
