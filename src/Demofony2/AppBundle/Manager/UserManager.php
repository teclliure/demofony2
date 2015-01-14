<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\UserBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\UserBundle\Model\UserManager as FOSUserManager;

/**
 * UserManager
 *
 * @package Demofony2\AppBundle\Manager
 */
class UserManager extends AbstractManager
{
    protected $userManager;

    /**
     * @param ObjectManager      $em
     * @param ValidatorInterface $validator
     * @param FOSUserManager        $um
     */
    public function __construct(ObjectManager $em, ValidatorInterface $validator, FOSUserManager $um)
    {
        parent::__construct($em, $validator);
        $this->userManager = $um;

    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('Demofony2UserBundle:User');
    }

    /**
     * @return User
     */
    public function create()
    {
        return new User();
    }

    /**
     * @param $username
     *
     * @return \FOS\UserBundle\Model\UserInterface
     */
    public function findByUsername($username)
    {
        return $this->userManager->findUserByUsername($username);
    }
}
