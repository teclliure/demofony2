<?php
namespace Demofony2\AppBundle\Repository;

use Demofony2\AppBundle\Entity\Comment;
use Demofony2\AppBundle\Entity\ProcessParticipation;

class CommentRepository extends BaseRepository
{
    public function getChildrenCommentInProcessParticipation($processParticipationId, $commentId, $page = 1, $limit = 10, $count = false)
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
}
