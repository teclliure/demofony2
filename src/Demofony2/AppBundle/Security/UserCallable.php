<?php
namespace Demofony2\AppBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * UserCallable can be invoked to return a blameable user
 * @see https://github.com/KnpLabs/DoctrineBehaviors/blob/master/src/Knp/DoctrineBehaviors/ORM/Blameable/UserCallable.php
 */
class UserCallable
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        $token = $this->container->get('security.token_storage')->getToken();
        if (null !== $token) {
            return $token->getUser();
        }

        return null;
    }
}