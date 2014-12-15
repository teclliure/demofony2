<?php

namespace Demofony2\AppBundle\Repository;

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
     * @param int $n
     * @return ArrayCollection
     */
    public function getNLastOpenProposals($n = self::MAX_LISTS_ITEMS)
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('p')
            ->where('p.finishAt > :now')
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
     * @param int $n
     * @return ArrayCollection
     */
    public function getNLastCloseProposals($n = self::MAX_LISTS_ITEMS)
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('p')
            ->where('p.finishAt <= :now')
            ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($n)
            ->getQuery()
            ->getResult();
    }
}
