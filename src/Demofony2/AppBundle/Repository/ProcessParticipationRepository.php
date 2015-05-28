<?php

namespace Demofony2\AppBundle\Repository;

use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ProcessParticipationRepository.
 *
 * @category Repository
 *
 * @author   David Romaní <david@flux.cat>
 */
class ProcessParticipationRepository extends BaseRepository
{
    const MAX_LISTS_ITEMS = 10;

    /**
     * Get 10 last open discussions.
     *
     * @return ArrayCollection
     */
    public function get10LastOpenDiscussions()
    {
        return $this->getNLastOpenDiscussions(self::MAX_LISTS_ITEMS);
    }

    /**
     * Get open discussions query.
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
            ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->orderBy('p.debateAt', 'DESC');
    }

    /**
     * Get closed discussions query.
     *
     * @return QueryBuilder
     */
    public function getClosedDiscussionsQueryBuilder()
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('p')
            ->select('p, d, pa, v')
            ->leftJoin('p.documents', 'd')
            ->leftJoin('p.proposalAnswers', 'pa')
            ->leftJoin('pa.votes', 'v')
            ->where('p.finishAt <= :now')
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
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.automaticState = :automaticState')
            ->andWhere('p.finishAt > :now OR p.state != :closed')
            ->setParameter('automaticState', true)
            ->setParameter('now', new \DateTime('now'))
            ->setParameter('closed', ProcessParticipationStateEnum::CLOSED)
        ;

        return $qb->getQuery();
    }
}
