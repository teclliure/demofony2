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

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;
use Demofony2\AppBundle\Entity\Proposal;

class ProposalVoter extends AbstractVoter
{
    const READ = 'read';

    protected $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $aci)
    {
        $this->authorizationChecker = $aci;
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

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($attribute == self::CREATE && in_array(ROLE_ADMIN, $user->getRoles())) {
            return true;
        }

        if ($attribute == self::EDIT && $user->getEmail() === $post->getAuthorEmail()) {
            return true;
        }

        return false;
    }
}