<?php
/**
 * Demofony2 app
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 23/03/14
 * Time: 12:23
 */
namespace Demofony2\AppBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Demofony2\AppBundle\Enum\UserRolesEnum;

class KernelRequestEvent
{
    protected $em;
    protected $securityContext;

    public function __construct(EntityManagerInterface $em, SecurityContextInterface $securityContext)
    {
        $this->em = $em;
        $this->securityContext = $securityContext;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $user = $this->getUser();
        $request = $event->getRequest();

        $filter = $this->em->getFilters()->enable('published_filter');
        $filter->enableForEntity('Demofony2\AppBundle\Entity\ProcessParticipation');

        if (is_object($user) && $this->securityContext->isGranted(UserRolesEnum::ROLE_ADMIN)) {
            $this->em->getFilters()->disable('published_filter');
        }
    }

    /**
     * Get a user from the Security Context
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see Symfony\Component\Security\Core\Authentication\Token\TokenInterface::getUser()
     */
    public function getUser()
    {
        if (null === $token = $this->securityContext->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }
}
