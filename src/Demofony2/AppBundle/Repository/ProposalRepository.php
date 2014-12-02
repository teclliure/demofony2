<?php
namespace Demofony2\AppBundle\Repository;

class ProposalRepository extends BaseRepository
{
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
}
