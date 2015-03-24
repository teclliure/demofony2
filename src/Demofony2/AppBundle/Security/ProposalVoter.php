<?php
/**
 * Demofony2 for Symfony2
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 * 
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com> 
 * 
 * Date: 24/03/15
 * Time: 12:23
 */
namespace Demofony2\AppBundle\Security;

use Demofony2\AppBundle\Enum\UserRolesEnum;
use Knp\Bundle\MenuBundle\EventListener\VoterInitializerListener;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Demofony2\AppBundle\Entity\Proposal;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ProposalVoter extends AbstractVoter
{
    const READ = 'read';

    protected $tokenStorage;
    protected $roleHierarchyVoter;

    public function __construct(TokenStorage $ts, RoleHierarchyVoter $rhv)
    {
        $this->tokenStorage = $ts;
        $this->roleHierarchyVoter = $rhv;
    }

    protected function getSupportedAttributes()
    {
        return array(self::READ);
    }

    protected function getSupportedClasses()
    {
        return array('Demofony2\AppBundle\Entity\Proposal');
    }

    protected function isGranted($attribute, $proposal, $user = null)
    {

        if (false === $proposal->getUserDraft() && false === $proposal->getModerationPending()) {
            return true;
        }

        if (!$user instanceof UserInterface) {
            return false;
        }

        $token = $this->tokenStorage->getToken();
        $respone = $this->roleHierarchyVoter->vote($token, null, array(UserRolesEnum::ROLE_ADMIN));

        if (VoterInterface::ACCESS_GRANTED === $respone) {
            return true;
        }

        return false;
    }
}