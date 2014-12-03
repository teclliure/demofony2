<?php
namespace Demofony2\AppBundle\Repository;

class ProcessParticipationRepository extends BaseRepository
{
    public function countProcessParticipationVoteByUser($userId, $processParticipationId)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('COUNT(v)')
            ->join('p.proposalAnswers', 'pa')
            ->join('pa.votes', 'v')
            ->join('v.author', 'u', 'WITH', 'u.id = :userId')
            ->where('p.id = :processParticipationId')
            ->setParameter('userId', $userId)
            ->setParameter('processParticipationId', $processParticipationId);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
