<?php

namespace Demofony2\UserBundle\Controller;

use Demofony2\UserBundle\Entity\User;
use FOS\UserBundle\Controller\ProfileController as FOSProfileController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $user = $this->container->get('app.user')->findByUsername($username);

        if (!$user instanceof User) {
            throw new NotFoundHttpException('Not Found');
        }
        // fake
        $comments = array(); // TODO fill with visible user comments sorted by date

        $paginator = $this->container->get('knp_paginator');
        $pagination = $paginator->paginate(
            $user->getProposals(),
            $request->query->get('pp', 1)/*page number*/,
            10, /*limit per page*/
            array('pageParameterName' => 'pp')
        );

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:show.html.twig',
            array(
                'user'      => $user,
                'comments'  => $comments,
                'proposals' => $pagination,
            )
        );
    }
}
