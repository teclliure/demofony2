<?php
/**
 * Author: Marc Morales Valldepérez marcmorales83@gmail.com
 * Date: 15/01/15
 * Time: 17:05
 */
namespace Demofony2\AppBundle\EventListener;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Demofony2\AppBundle\Enum\UserRolesEnum;

/**
 * CompleteUserProfileEventListener
 */
class CompleteUserProfileEventListener
{
    protected $token;
    protected $router;
    protected $environment;
    protected $session;

    /**
     * @param TokenStorageInterface $token
     * @param RouterInterface       $router
     * @param SessionInterface      $session
     */
    public function __construct(TokenStorageInterface $token, RouterInterface $router, SessionInterface $session)
    {
        $this->token = $token;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $token = $this->token->getToken();

        if (null === $token) {
            return;
        }
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $route = $event->getRequest()->attributes->get('_route');
//
        if ($user->hasRole(UserRolesEnum::ROLE_PENDING_COMPLETE_PROFILE) && 'fos_user_profile_edit' !== $route && 'fos_user_security_logout' !== $route && HttpKernel::MASTER_REQUEST === $event->getRequestType()) {
            $url = $this->router->generate('fos_user_profile_edit', array('username' => $user->getUsername()));
            $response = new RedirectResponse($url);
            $event->setResponse($response);
            $this->addFlash('user.form.profile.complete_profile');

            return;
        }
    }

    /**
     * @param string $message
     * @param string $type
     */
    protected function addFlash($message, $type = 'info')
    {
        $this->session->getFlashBag()->add(
            $type,
            $message
        );
    }
}
