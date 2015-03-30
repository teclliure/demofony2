<?php
namespace Demofony2\AppBundle\Repository;

use Demofony2\UserBundle\Entity\User;

class CommentRepository extends BaseRepository
{
    public function getChildrenCommentByProcessParticipation(
        $processParticipationId,
        $commentId,
        $page = 1,
        $limit = 10,
        $count = false
    ) {
        $qb = $this->createQueryBuilder('c');

        if ($count) {
            $qb->select('COUNT(c.id)');
        } else {
            $qb->select('pp,c,a');
        }

        $qb
            ->innerJoin('c.processParticipation', 'pp', 'WITH', 'pp.id = :id')
            ->leftjoin('c.author', 'a')
            ->Where('c.root = :root')
            ->andWhere('c.lvl >= :lvl')
            ->andWhere('c.moderated = :commentsModerated')
            ->setParameter('id', $processParticipationId)
            ->setParameter('lvl', 1)
            ->setParameter('root', $commentId)
            ->setParameter('commentsModerated', false);

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
            $qb->select('pp,c,a');
        }

        $qb->innerJoin('c.processParticipation', 'pp', 'WITH', 'pp.id = :id')
            ->leftjoin('c.author', 'a')
            ->where('c.lvl = :lvl')
            ->andWhere('c.moderated = :commentsModerated')
            ->setParameter('id', $id)
            ->setParameter('lvl', 0)
            ->setParameter('commentsModerated', false);

        if ($count) {
            return $qb->getQuery()->getSingleScalarResult();
        }

        $qb->orderBy('c.createdAt', 'DESC');

        return $this->paginateQuery($qb->getQuery(), $page, $limit);
    }

    public function getNotModeratedCountByProcessParticipation($id)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select('COUNT(c.id)')
            ->innerJoin('c.processParticipation', 'pp', 'WITH', 'pp.id = :id')
            ->where('c.moderated = :commentsModerated')
            ->setParameter('id', $id)
            ->setParameter('commentsModerated', false);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getChildrenCommentByCitizenForum(
        $citizenForumId,
        $commentId,
        $page = 1,
        $limit = 10,
        $count = false
    ) {
        $qb = $this->createQueryBuilder('c');

        if ($count) {
            $qb->select('COUNT(c.id)');
        } else {
            $qb->select('cf,c,a');
        }

        $qb
            ->innerJoin('c.citizenForum', 'cf', 'WITH', 'cf.id = :id')
            ->leftjoin('c.author', 'a')
            ->Where('c.root = :root')
            ->andWhere('c.lvl >= :lvl')
            ->andWhere('c.moderated = :commentsModerated')
            ->setParameter('id', $citizenForumId)
            ->setParameter('lvl', 1)
            ->setParameter('root', $commentId)
            ->setParameter('commentsModerated', false);

        if ($count) {
            return $qb->getQuery()->getSingleScalarResult();
        }
        $qb->orderBy('c.lft', 'ASC');

        return $this->paginateQuery($qb->getQuery(), $page, $limit);
    }

    public function getCommentsByCitizenForum($id, $page = 1, $limit = 10, $count = false)
    {
        $qb = $this->createQueryBuilder('c');

        if ($count) {
            $qb->select('COUNT(c.id)');
        } else {
            $qb->select('cf,c,a');
        }

        $qb->innerJoin('c.citizenForum', 'cf', 'WITH', 'cf.id = :id')
            ->leftjoin('c.author', 'a')
            ->where('c.lvl = :lvl')
            ->andWhere('c.moderated = :commentsModerated')
            ->setParameter('id', $id)
            ->setParameter('lvl', 0)
            ->setParameter('commentsModerated', false);

        if ($count) {
            return $qb->getQuery()->getSingleScalarResult();
        }

        $qb->orderBy('c.createdAt', 'DESC');

        return $this->paginateQuery($qb->getQuery(), $page, $limit);
    }

    public function getNotModeratedCountByCitizenForum($id)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select('COUNT(c.id)')
            ->innerJoin('c.citizenForum', 'cf', 'WITH', 'cf.id = :id')
            ->where('c.moderated = :commentsModerated')
            ->setParameter('id', $id)
            ->setParameter('commentsModerated', false);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getCommentsByProposal($id, $page = 1, $limit = 10, $count = false)
    {
        $qb = $this->createQueryBuilder('c');

        if ($count) {
            $qb->select('COUNT(c.id)');
        } else {
            $qb->select('p,c,a');
        }

        $qb->innerJoin('c.proposal', 'p', 'WITH', 'p.id = :id')
            ->leftjoin('c.author', 'a')
            ->where('c.lvl = :lvl')
            ->andWhere('c.moderated = :commentsModerated')
            ->setParameter('id', $id)
            ->setParameter('lvl', 0)
            ->setParameter('commentsModerated', false);

        if ($count) {
            return $qb->getQuery()->getSingleScalarResult();
        }

        $qb->orderBy('c.createdAt', 'DESC');

        return $this->paginateQuery($qb->getQuery(), $page, $limit);
    }

    public function getChildrenCommentByProposal($proposalId, $commentId, $page = 1, $limit = 10, $count = false)
    {
        $qb = $this->createQueryBuilder('c');

        if ($count) {
            $qb->select('COUNT(c.id)');
        } else {
            $qb->select('p,c,a');
        }

        $qb
            ->innerJoin('c.proposal', 'p', 'WITH', 'p.id = :id')
            ->leftjoin('c.author', 'a')
            ->Where('c.root = :root')
            ->andWhere('c.lvl >= :lvl')
            ->andWhere('c.moderated = :commentsModerated')
            ->setParameter('id', $proposalId)
            ->setParameter('lvl', 1)
            ->setParameter('root', $commentId)
            ->setParameter('commentsModerated', false);
        if ($count) {
            return $qb->getQuery()->getSingleScalarResult();
        }
        $qb->orderBy('c.lft', 'ASC');

        return $this->paginateQuery($qb->getQuery(), $page, $limit);
    }

    public function getLikesCount($commentId, $userId = null)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select('COUNT(v.id)')
            ->join('Demofony2AppBundle:CommentVote', 'v', 'WITH', 'v.comment = :commentId');

        if (isset($userId)) {
            $qb->join('v.author', 'a', 'WITH', 'a.id = :userId')
                ->setParameter('userId', $userId);
        }

        $qb->Where('c.id = :commentId')
            ->andWhere('v.value = :value')
            ->setParameter('commentId', $commentId)
            ->setParameter('value', true);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getUnLikesCount($commentId, $userId = null)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select('COUNT(v.id)')
            ->join('Demofony2AppBundle:CommentVote', 'v', 'WITH', 'v.comment = :commentId');

        if (isset($userId)) {
            $qb->join('v.author', 'a', 'WITH', 'a.id = :userId')
                ->setParameter('userId', $userId);
        }

        $qb->Where('c.id = :commentId')
            ->andWhere('v.value = :value')
            ->setParameter('commentId', $commentId)
            ->setParameter('value', false);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getUnrevisedCount()
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select('COUNT(c.id)')
            ->where('c.revised = :revised')
            ->setParameter('revised', false);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getPublishedBetweenDate($startAt, $endAt, $count = true)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select('COUNT(c.id)')
            ->where('c.createdAt >= :startAt')
            ->andWhere('c.createdAt < :endAt')
            ->setParameter('startAt', $startAt)
            ->setParameter('endAt', $endAt);

        if (!$count) {
            $qb->select('c');

            return  $qb->getQuery()->getResult();
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function queryByUser(User $user)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select('c')
            ->where('c.author = :user')
            ->andWhere('c.moderated = :commentsModerated')
            ->addOrderBy('c.createdAt', 'DESC')
            ->setParameter('user', $user)
            ->setParameter('commentsModerated', false);

        return $qb->getQuery();
    }
}
