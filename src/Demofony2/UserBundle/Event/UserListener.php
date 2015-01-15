<?php

namespace Demofony2\UserBundle\Event;

use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UserListener  implements EventSubscriberInterface
{
    private static $successMessages = array(
        FOSUserEvents::REGISTRATION_SUCCESS => 'registration.success.check_email',

    );

    private $session;
    private $translator;
    private $router;

    public function __construct(TranslatorInterface $translatorInterface, SessionInterface $session, RouterInterface $router)
    {
        $this->session = $session;
        $this->translator = $translatorInterface;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'addSuccessFlash',
            FOSUserEvents::PROFILE_EDIT_SUCCESS => 'onProfileEditSuccess',
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onProfileEditSuccess',
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onResetPasswordSuccess',
        );
    }

    public function onResetPasswordSuccess(FormEvent $event)
    {
        $user = $event->getForm()->getData();
        $url = $this->router->generate('demofony2_front_homepage');
        $response = $event->setResponse(new RedirectResponse($url));

        return $response;
    }

    public function onProfileEditSuccess(FormEvent $event)
    {
        $user = $event->getForm()->getData();
        $url = $this->router->generate('fos_user_profile_public_show', array('username' => $user->getUsername()));
        $response = $event->setResponse(new RedirectResponse($url));

        return $response;
    }

    public function addSuccessFlash(Event $event)
    {
        if (!isset(self::$successMessages[$event->getName()])) {
            throw new \InvalidArgumentException('This event does not correspond to a known flash message');
        }

        /** @Ignore */
        $message = $this->trans(self::$successMessages[$event->getName()]);
        $this->session->getFlashBag()->add('success', $message);
    }

    private function trans($message, array $params = array())
    {
        /** @Ignore */
        return $this->translator->trans($message, $params);
    }
}
