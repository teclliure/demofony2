<?php
namespace Demofony2\AppBundle\Repository;


class VoteRepository extends BaseRepository
{
    public function countProcessParticipationVoteByUser($userId, $processParticipationId)
    {
        $qb = $this->createQueryBuilder('v');

        $qb->select('COUNT(v)')
            ->join('Demofony2AppBundle:ProposalAnswer', 'pa', 'WITH', 'pa.votes = v.id')
            ->innerJoin('Demofony2AppBundle:ProcessParticipation', 'pp', 'WITH', 'pp.proposalAnswers = pa')
            ->innerJoin('v.author', 'u', 'WITH', 'u.id = :userId')
            ->where('pp.id = :processParticipationId')
            ->setParameter('userId', $userId)
            ->setParameter('processParticipation', $processParticipationId);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
