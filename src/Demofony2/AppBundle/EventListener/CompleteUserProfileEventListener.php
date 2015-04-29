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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

/**
 * CompleteUserProfileEventListener
 */
class CompleteUserProfileEventListener
{
    protected $token;
    protected $router;
    protected $environment;
    protected $session;
    protected $authorizationChecker;
    protected $translator;

    /**
     * @param TokenStorageInterface $token
     * @param RouterInterface $router
     * @param SessionInterface $session
     * @param AuthorizationCheckerInterface $ac
     * @param Translator $translator
     */
    public function __construct(
        TokenStorageInterface $token,
        RouterInterface $router,
        SessionInterface $session,
        AuthorizationCheckerInterface $ac,
        Translator $translator
    ) {
        $this->token = $token;
        $this->router = $router;
        $this->session = $session;
        $this->authorizationChecker = $ac;
        $this->translator = $translator;
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

        if ($this->authorizationChecker->isGranted(UserRolesEnum::ROLE_ADMIN)) {
            return;
        }

        $route = $event->getRequest()->attributes->get('_route');

        if ($user->hasRole(
                'ROLE_PENDING_COMPLETE_PROFILE'
            ) && 'fos_user_profile_edit' !== $route && 'fos_user_security_logout' !== $route && HttpKernel::MASTER_REQUEST === $event->getRequestType(
            )
        ) {
            $url = $this->router->generate('fos_user_profile_edit', array('username' => $user->getUsername()));
            $response = new RedirectResponse($url);
            $event->setResponse($response);
            $this->addFlash($this->translator->trans('user.form.profile.complete_profile'));

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
