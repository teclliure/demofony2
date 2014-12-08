<?php
namespace Demofony2\AppBundle\Repository;

class ProcessParticipationRepository extends BaseRepository
{
    public function processParticipationVoteByUser($userId, $processParticipationId, $count = true)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->join('p.proposalAnswers', 'pa')
            ->join('pa.votes', 'v')
            ->join('v.author', 'u', 'WITH', 'u.id = :userId')
            ->where('p.id = :processParticipationId')
            ->setParameter('userId', $userId)
            ->setParameter('processParticipationId', $processParticipationId);

        if ($count) {
            $qb->select('COUNT(v)');
        } else {
            $qb->select('vote')
                ->from('Demofony2AppBundle:Vote', 'vote');
        }



        if ($count) {
            return $qb->getQuery()->getSingleScalarResult();
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
