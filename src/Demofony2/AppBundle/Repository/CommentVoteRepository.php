<?php

namespace Demofony2\AppBundle\Repository;

class CommentVoteRepository extends BaseRepository
{
    public function getVoteByCommentAndUserAndValue($commentId, $userId, $value)
    {
        $qb = $this->createQueryBuilder('v');
        $qb->select('v')
            ->join('v.author', 'u', 'WITH', 'u.id = :userId')
            ->join('v.comment', 'c', 'WITH', 'c.id = :commentId')
            ->where('v.value = :value')
            ->setParameter('userId', $userId)
            ->setParameter('commentId', $commentId)
            ->setParameter('value', $value);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
