<?php

namespace Demofony2\AppBundle\Repository;

use Demofony2\AppBundle\Enum\ProposalStateEnum;
use Demofony2\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class ProposalRepository
 *
 * @category Repository
 * @package  Demofony2\AppBundle\Repository
 */
class ProposalRepository extends BaseRepository
{
    const MAX_LISTS_ITEMS = 10;

    public function countProposalVoteByUser($userId, $proposalId)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('COUNT(v)')
            ->join('p.proposalAnswers', 'pa')
            ->join('pa.votes', 'v')
            ->join('v.author', 'u', 'WITH', 'u.id = :userId')
            ->where('p.id = :proposalId')
            ->setParameter('userId', $userId)
            ->setParameter('proposalId', $proposalId);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Get 10 last open proposals
     *
     * @return ArrayCollection
     */
    public function get10LastOpenProposals()
    {
        return $this->getNLastOpenProposals(self::MAX_LISTS_ITEMS);
    }

    /**
     * Get n last open proposals
     *
     * @param  int             $n
     * @return ArrayCollection
     */
    public function getNLastOpenProposals($n = self::MAX_LISTS_ITEMS)
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('p')
            ->select('p,d,pa,v')
            ->leftJoin('p.documents', 'd')
            ->leftJoin('p.proposalAnswers', 'pa')
            ->leftJoin('pa.votes', 'v')
            ->where('p.finishAt > :now')
            ->andWhere('p.userDraft = false')
            ->andWhere('p.moderationPending = false')
            ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($n)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get 10 last close proposals
     *
     * @return ArrayCollection
     */
    public function get10LastCloseProposals()
    {
        return $this->getNLastCloseProposals(self::MAX_LISTS_ITEMS);
    }

    /**
     * Get n last close proposals
     *
     * @param  int             $n
     * @return ArrayCollection
     */
    public function getNLastCloseProposals($n = self::MAX_LISTS_ITEMS)
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('p')
            ->select('p,d,pa,v')
            ->leftJoin('p.documents', 'd')
            ->leftJoin('p.proposalAnswers', 'pa')
            ->leftJoin('pa.votes', 'v')
            ->where('p.finishAt <= :now')
            ->andWhere('p.userDraft = false')
            ->andWhere('p.moderationPending = false')
            ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($n)
            ->getQuery()
            ->getResult();
    }

    public function getVotePeriodCount()
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('COUNT(p.id)')
            ->where('p.state = :state')
            ->andWhere('p.finishAt > :now')
            ->setParameter('state', ProposalStateEnum::DEBATE)
            ->setParameter('now', new \DateTime());

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getClosedWithoutInstitutionalAnswerCount()
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('COUNT(p.id)')
            ->where('p.finishAt < :now')
            ->andWhere('p.institutionalAnswer is NULL')
            ->setParameter('now', new \DateTime());

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getPublishedBetweenDate($startAt, $endAt, $count = true)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('COUNT(p.id)')
            ->where('p.createdAt >= :startAt')
            ->andWhere('p.createdAt < :endAt')
            ->setParameter('startAt', $startAt)
            ->setParameter('endAt', $endAt);

        if (!$count) {
            $qb->select('p');

            return  $qb->getQuery()->getResult();
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function queryAllToUpdateState()
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.userDraft = :false')
            ->andWhere('p.finishAt > :now OR p.state != :closed')
            ->setParameter('false', false)
            ->setParameter('now', new \DateTime('now'))
            ->setParameter('closed', ProposalStateEnum::CLOSED)
            ;

        return $qb->getQuery();
    }

    public function queryByUserProfileAndUserLogged(User $user, $loggedUser)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.createdAt', 'DESC')
            ->where('p.author = :author')
            ->setParameter('author', $user);

        if ($user !== $loggedUser) {
            $qb->andwhere('p.userDraft = :false')
                ->andWhere('p.moderationPending = :false')
                ->setParameter('false', false);
        }

        return $qb->getQuery();
    }
}
