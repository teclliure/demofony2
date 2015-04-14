<?php

namespace Demofony2\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getEmailsNewsletterSubscribed()
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u.email as email, u.name as name')
             ->where('u.enabled = :true')
             ->andWhere('u.newsletterSubscribed = :true')
            ->setParameter('true', true)
        ;

        return $qb->getQuery()->getArrayResult();
    }
}
