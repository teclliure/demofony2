<?php
/**
 * Demofony2
 * 
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 * 
 * Date: 15/01/15
 * Time: 15:59
 */
namespace Demofony2\AppBundle\AuthenticationHandler;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * AuthenticationHandler
 *
 * @package Demofony2\AppBundle\AuthenticationHandler
 */
class AuthenticationHandler implements AuthenticationFailureHandlerInterface, LogoutSuccessHandlerInterface
{
    private $router;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function onLogoutSuccess(Request $request)
    {
        return $this->customRedirect($request);
    }

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set('_security.last_error', $exception);

        return $this->customRedirect($request);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    private function customRedirect(Request $request)
    {
        $referer = $request->headers->get('referer');

        if (empty($referer)) {
            return new RedirectResponse($this->router->generate('demofony2_front_homepage'));
        }

        return new RedirectResponse($referer);
    }
}