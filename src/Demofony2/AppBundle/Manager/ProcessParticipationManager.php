<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\AppBundle\Entity\ProcessParticipation;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProcessParticipationManager extends AbstractManager
{
        /**
     * @param ObjectManager      $em
     * @param ValidatorInterface $validator
     */
    public function __construct(ObjectManager $em, ValidatorInterface  $validator)
    {
        parent::__construct($em, $validator);
    }

    /**
     * @return \Demofony2\AppBundle\Repository\ProcessParticipationRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('Demofony2AppBundle:ProcessParticipation');
    }

    /**
     * @return ProcessParticipation
     */
    public function create()
    {
        return new ProcessParticipation();
    }

    public function getComments($id, $page, $limit)
    {
        return $this->getRepository()->getComments($id, $page, $limit);
    }

}
