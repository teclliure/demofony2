<?php

namespace Demofony2\UserBundle\Controller;

use Demofony2\UserBundle\Entity\User;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\ProfileController as FOSProfileController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends FOSProfileController
{
    /**
     * @param string $username
     *
     * @return Response
     */
    public function showPublicProfileAction($username)
    {
        $user = $this->get('app.user')->findByUsername($username);

        if ($user) {
            $this->createNotFoundException();
        }

        // fake
        $comments = array(); // fill with visible user comments sorted by date

        return $this->render('Front/profile.html.twig', array(
            'user'     => $user,
            'comments' => $comments,
        ));
    }
}
