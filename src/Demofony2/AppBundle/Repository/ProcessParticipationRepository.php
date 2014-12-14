<?php

namespace Demofony2\AppBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class ProcessParticipationRepository
 *
 * @category Repository
 * @package  Demofony2\AppBundle\Repository
 * @author   David Romaní <david@flux.cat>
 */
class ProcessParticipationRepository extends BaseRepository
{
    const MAX_LISTS_ITEMS = 10;

    /**
     * Get 10 last open discussions
     *
     * @return ArrayCollection
     */
    public function get10LastOpenDiscussions()
    {
        return $this->getNLastOpenDiscussions(self::MAX_LISTS_ITEMS);
    }

    /**
     * Get n last open discussions
     *
     * @param int $n
     * @return ArrayCollection
     */
    public function getNLastOpenDiscussions($n = self::MAX_LISTS_ITEMS)
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('p')
            ->where('p.presentationAt < :now')
            ->andWhere('p.finishAt > :now')
            ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->orderBy('p.presentationAt', 'DESC')
            ->setMaxResults($n)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get 10 last close discussions
     *
     * @return ArrayCollection
     */
    public function get10LastCloseDiscussions()
    {
        return $this->getNLastCloseDiscussions(self::MAX_LISTS_ITEMS);
    }

    /**
     * Get n last close discussions
     *
     * @param int $n
     * @return ArrayCollection
     */
    public function getNLastCloseDiscussions($n = self::MAX_LISTS_ITEMS)
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('p')
            ->where('p.finishAt <= :now')
            ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->orderBy('p.presentationAt', 'DESC')
            ->setMaxResults($n)
            ->getQuery()
            ->getResult();
    }
}
