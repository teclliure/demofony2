<?php

namespace Demofony2\AppBundle\Repository;

use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;
use Doctrine\Common\Collections\ArrayCollection;

class CitizenForumRepository extends BaseRepository
{

//    public function getWithJoins($id)
//    {
//        $qb = $this->createQueryBuilder('p')
//            ->select('p,d,i,c, gps, pa')
//            ->leftJoin('p.documents', 'd')
//            ->leftJoin('p.images', 'i')
//            ->leftJoin('p.categories', 'c')
//            ->leftJoin('p.gps', 'gps')
//            ->leftJoin('p.proposalAnswers', 'pa')
//            ->where('p.id = :id')
//            ->setParameter('id', $id)
//            ;
//
//        return $qb->getQuery()->getOneOrNullResult();
//    }

//    public function queryAllToUpdateState()
//    {
//        $qb = $this->createQueryBuilder('p')
//            ->select('p')
//            ->where('p.automaticState = :automaticState')
//            ->andWhere('p.finishAt > :now OR p.state != :closed')
//            ->setParameter('automaticState', true)
//            ->setParameter('now', new \DateTime('now'))
//            ->setParameter('closed', ProcessParticipationStateEnum::CLOSED)
//        ;
//
//        return $qb->getQuery();
//    }
}
