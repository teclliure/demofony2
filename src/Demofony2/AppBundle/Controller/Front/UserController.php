<?php

namespace Demofony2\AppBundle\Controller\Front;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Demofony2\UserBundle\Entity\User;

/**
 * Class UserController
 *
 * @category Controller
 * @package  Demofony2\AppBundle\Controller\Front
 * @author   David Romaní <david@flux.cat>
 * @author   Marc Morales <marcmorales83@gmail.com>
 */
class UserController extends Controller
{

    /**
     * @param string $username
     * @Route("/profile/{username}/", name="demofony2_front_profile")
     *
     * @return Response
     */
    public function publicProfileAction($username)
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

    /**
     * @param string $username
     * @Route("/profile/{username}/edit", name="demofony2_front_profile")
     * @Security("has_role('ROLE_USER')")
     *
     * @return Response
     */
    public function editProfileAction($username)
    {
        $user = $this->getUser();


        // fake
        $comments = array(); // fill with visible user comments sorted by date

        return $this->render('Front/profile.html.twig', array(
            'user'     => $user,
            'comments' => $comments,
        ));
    }


}
