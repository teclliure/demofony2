<?php
namespace Demofony2\AppBundle\Repository;

class VoteRepository extends BaseRepository
{
    public function getVoteByUserInProcessParticipation($userId, $processParticipationId, $count = false)
    {
        $qb = $this->createQueryBuilder('v');

        $qb->select('v')
            ->join('Demofony2AppBundle:ProposalAnswer', 'pa', 'WITH', 'v MEMBER OF pa.votes')
            ->join('Demofony2AppBundle:ProcessParticipation', 'pp', 'WITH', 'pa MEMBER OF pp.proposalAnswers')
            ->innerJoin('v.author', 'u', 'WITH', 'u.id = :userId')
            ->where('pp.id = :processParticipationId')
            ->setParameter('userId', $userId)
            ->setParameter('processParticipationId', $processParticipationId);

        if ($count) {
            $qb->select('COUNT(v.id)');

            return $qb->getQuery()->getSingleScalarResult();
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getVoteByUserInProposal($userId, $proposalId, $count = false)
    {
        $qb = $this->createQueryBuilder('v');

        $qb->select('v')
            ->join('Demofony2AppBundle:ProposalAnswer', 'pa', 'WITH', 'v MEMBER OF pa.votes')
            ->join('Demofony2AppBundle:Proposal', 'p', 'WITH', 'pa MEMBER OF p.proposalAnswers')
            ->innerJoin('v.author', 'u', 'WITH', 'u.id = :userId')
            ->where('p.id = :proposalId')
            ->setParameter('userId', $userId)
            ->setParameter('proposalId', $proposalId);

        if ($count) {
            $qb->select('COUNT(v.id)');

            return $qb->getQuery()->getSingleScalarResult();
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getVoteByUserInProposalAnswer($userId, $proposalAnswerId)
    {
        $qb = $this->createQueryBuilder('v');

        $qb->select('COUNT(v.id)')
            ->join('Demofony2AppBundle:ProposalAnswer', 'pa', 'WITH', 'v MEMBER OF pa.votes')
            ->innerJoin('v.author', 'u', 'WITH', 'u.id = :userId')
            ->where('pa.id = :proposalAnswerId')
            ->setParameter('userId', $userId)
            ->setParameter('proposalAnswerId', $proposalAnswerId);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
