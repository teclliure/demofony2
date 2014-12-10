<?php

namespace Demofony2\AppBundle\Manager;


use Demofony2\AppBundle\Entity\Comment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;
use Demofony2\AppBundle\Entity\CommentVote;

class CommentVoteManager extends AbstractManager
{
    /**
     * @param ObjectManager                $em
     * @param ValidatorInterface           $validator
     */
    public function __construct(ObjectManager $em, ValidatorInterface $validator)
    {
        parent::__construct($em, $validator);
    }

    /**
     * @return \Demofony2\AppBundle\Repository\CommentVoteRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('Demofony2AppBundle:CommentVote');
    }

    public function create()
    {

    }
}
