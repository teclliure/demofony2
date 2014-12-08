<?php
namespace Demofony2\AppBundle\Repository;

class VoteRepository extends BaseRepository
{
    public function getVoteByUser($userId, $processParticipationId)
    {
        $qb = $this->createQueryBuilder('v');

        $qb->select('v')
            ->join('Demofony2AppBundle:ProposalAnswer', 'pa', 'WITH', 'v MEMBER OF pa.votes')
            ->join('Demofony2AppBundle:ProcessParticipation', 'pp', 'WITH', 'pa MEMBER OF pp.proposalAnswers')
            ->innerJoin('v.author', 'u', 'WITH', 'u.id = :userId')
            ->where('pp.id = :processParticipationId')
            ->setParameter('userId', $userId)
            ->setParameter('processParticipationId', $processParticipationId);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
