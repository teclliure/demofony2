<?php

namespace Demofony2\AppBundle\Security;

use Demofony2\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * UserCallable can be invoked to return a blameable user.
 *
 * @see https://github.com/KnpLabs/DoctrineBehaviors/blob/master/src/Knp/DoctrineBehaviors/ORM/Blameable/UserCallable.php
 */
class UserCallable
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        $token = $this->tokenStorage->getToken();
        if (null !== $token && ($user = $token->getUser()) instanceof User) {
            return $user;
        }

        return;
    }
}
