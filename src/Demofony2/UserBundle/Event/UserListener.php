<?php

namespace Demofony2\UserBundle\Event;

use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\Event;
use FOS\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\Event\FormEvent;

class UserListener  implements EventSubscriberInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        );
    }

    public function onRegistrationSuccess(GetResponseUserEvent $event)
    {
        $url = $this->router->generate('demofony2_front_homepage');
        $response = $event->setResponse( new RedirectResponse($url));

        return $response;
    }
}
