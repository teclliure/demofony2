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

/**
 * Class UserController
 *
 * @category Controller
 * @package  Demofony2\AppBundle\Controller\Front
 * @author   David Romaní <david@flux.cat>
 */
class UserController extends Controller
{
    public function registerAction(Request $request)
    {
        /** @var FactoryInterface $formFactory */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var UserManagerInterface $userManager */
        $userManager = $this->get('fos_user.user_manager');
        /** @var EventDispatcherInterface $dispatcher */

        $dispatcher = $this->get('event_dispatcher');
        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $registerForm = $formFactory->createForm();
        $registerForm->setData($user);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $event = new FormEvent($registerForm, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
            $userManager->updateUser($user);
            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('demofony2_front_homepage'); // URL doesn't matter because we are not redirecting (see twig render controller)
                $response = new RedirectResponse($url);
            }
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            //return $response;    // disallow redirect
        }

        return $this->render('Front/includes/navbar-register.html.twig', array(
                'registerForm' => $registerForm->createView(),
            ));
    }
}
