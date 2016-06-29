<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Demofony2\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as FOSRegistrationController;

/**
 * Controller managing the registration.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends FOSRegistrationController
{
    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // Check if user already exists
            $userFound = $this->findUserEmail($user->getEmail());
            if ($userFound) {
                // If it is deleted we should reactivate user
                if ($userFound->getRemovedAt()) {
                    $userFound->setRemovedAt(null);
                }
                if (null === $userFound->getConfirmationToken()) {
                    /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
                    $tokenGenerator = $this->get('fos_user.util.token_generator');
                    $userFound->setConfirmationToken($tokenGenerator->generateToken());
                }

                $this->get('fos_user.mailer')->sendResettingEmailMessage($userFound);
                $user->setPasswordRequestedAt(new \DateTime());
                $this->get('fos_user.user_manager')->updateUser($userFound);

                $this->addFlash('info', $this->get('translator')->trans('user_already_exist_reset_confirmation'));

                return $this->redirectToRoute('demofony2_front_homepage');
            }
            else {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
                $form = $formFactory->createForm();
                $form->setData($user);
                $form = $formFactory->createForm();
            }
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function findUserEmail($email)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('softdeleteable');
        $query = $em->createQuery(
            'SELECT u
            FROM Demofony2UserBundle:User u
            WHERE u.email LIKE :email'
        )->setParameter('email', '%'.$email.'%');

        $user = $query->getOneOrNullResult();

        $em->getFilters()->enable('softdeleteable');

        return $user;
    }
}
