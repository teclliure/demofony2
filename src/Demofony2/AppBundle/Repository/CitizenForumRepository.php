<?php

namespace Demofony2\AppBundle\Repository;

use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;
use Doctrine\ORM\QueryBuilder;

/**
 * Class CitizenForumRepository.
 *
 * @category Repository
 *
 * @author   David Romaní <david@flux.cat>
 */
class CitizenForumRepository extends BaseRepository
{
    /**
     * Get open discussions query builder.
     *
     * @return QueryBuilder
     */
    public function getOpenDiscussionsQueryBuilder()
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('p')
            ->select('p, d, pa, v')
            ->leftJoin('p.documents', 'd')
            ->leftJoin('p.proposalAnswers', 'pa')
            ->leftJoin('pa.votes', 'v')
            ->where('p.finishAt > :now')
            ->andWhere('p.published = 1')
            ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->orderBy('p.debateAt', 'DESC');
    }

    /**
     * Get n last close discussions.
     *
     * @return QueryBuilder
     */
    public function getCloseDiscussionsQueryBuilder()
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('p')
            ->select('p,d,pa,v')
            ->leftJoin('p.documents', 'd')
            ->leftJoin('p.proposalAnswers', 'pa')
            ->leftJoin('pa.votes', 'v')
            ->where('p.finishAt <= :now')
            ->andWhere('p.published = 1')
            ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->orderBy('p.debateAt', 'DESC');
    }

    public function getWithJoins($id)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p,d,c, gps, pa')
            ->leftJoin('p.documents', 'd')
            ->leftJoin('p.categories', 'c')
            ->leftJoin('p.gps', 'gps')
            ->leftJoin('p.proposalAnswers', 'pa')
            ->where('p.id = :id')
            ->setParameter('id', $id)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }


    public function queryAllToUpdateState()
    {
        $qb = $this->createQueryBuilder('cf')
            ->select('cf')
            ->where('cf.automaticState = :automaticState')
            ->andWhere('cf.finishAt > :now OR cf.state != :closed')
            ->setParameter('automaticState', true)
            ->setParameter('now', new \DateTime('now'))
            ->setParameter('closed', ProcessParticipationStateEnum::CLOSED)
        ;

        return $qb->getQuery();
    }
}
