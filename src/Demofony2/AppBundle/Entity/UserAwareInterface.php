<?php
/**
 * Demofony2
 * 
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 * 
 * Date: 25/11/14
 * Time: 12:57
 */
namespace Demofony2\AppBundle\Entity;

use Demofony2\UserBundle\Entity\User;

interface UserAwareInterface
{
    /**
     * Sets the user
     *
     * @param User|null $user A user instance or null
     */
    public function setAuthor(User $user = null);
}