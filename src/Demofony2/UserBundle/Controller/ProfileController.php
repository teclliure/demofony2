<?php

namespace Demofony2\UserBundle\Controller;

use Demofony2\UserBundle\Entity\User;
use FOS\UserBundle\Controller\ProfileController as FOSProfileController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends FOSProfileController
{
    /**
     * @param Request $request
     * @param string  $username
     *
     * @return Response
     */
    public function showPublicProfileAction(Request $request, $username)
    {
        $user = $this->get('app.user')->findByUsername($username);

        if (!$user instanceof User) {
            throw $this->createNotFoundException();
        }
        // fake
        $comments = array(); // fill with visible user comments sorted by date

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $user->getProposals(),
            $request->query->get('pp', 1)/*page number*/,
            1, /*limit per page*/
            array('pageParameterName' => 'pp')
      );

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'user' => $user,
            'comments' => $comments,
            'proposals' => $pagination,
        ));
    }
}
