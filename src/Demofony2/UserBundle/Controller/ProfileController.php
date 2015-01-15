<?php

namespace Demofony2\UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\ProfileController as FOSProfileController;

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

        if (!$user instanceof UserInterface) {
            throw $this->createNotFoundException();
        }
        // fake
        $comments = array(); // fill with visible user comments sorted by date

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'user' => $user,
            'comments' => $comments,
        ));
    }
}
