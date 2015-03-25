<?php

namespace Demofony2\UserBundle\Controller;

use Demofony2\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
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

        if (!$user instanceof User || !$user->isEnabled()) {
            throw new NotFoundHttpException('Not Found');
        }

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $comments = $em->getRepository('Demofony2AppBundle:Comment')->getByUser($user);

        $paginator = $this->container->get('knp_paginator');

        $proposalsPagination = $paginator->paginate(
            $user->getProposals(),
            $request->query->get('pp', 1)/*page number*/,
            10, /*limit per page*/
            array('pageParameterName' => 'pp')
        );

        $commentsPagination = $paginator->paginate(
            $comments,
            $request->query->get('cp', 1)/*page number*/,
            10, /*limit per page*/
            array('pageParameterName' => 'cp')
        );

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:show.html.twig',
            array(
                'user'      => $user,
                'comments'  => $commentsPagination,
                'proposals' => $proposalsPagination,
            )
        );
    }
}
