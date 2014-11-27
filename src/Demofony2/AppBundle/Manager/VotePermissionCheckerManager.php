<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\UserBundle\Entity\User;
use Demofony2\AppBundle\Entity\ProcessParticipation;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;
use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;

class VotePermissionCheckerManager
{
    protected $em;
    protected $validator;
    protected $securityContext;

    /**
     * @param ObjectManager            $em
     * @param ValidatorInterface       $validator
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(ObjectManager $em, ValidatorInterface $validator, SecurityContextInterface $securityContext)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->securityContext = $securityContext;
    }

    public function checkUserHasVoteInProcessParticipation(ProcessParticipation $processParticipation)
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $processParticipationId = $processParticipation->getId();
        $result = (int) $this->em->getRepository('Demofony2AppBundle:ProcessParticipation')->countProcessParticipationVoteByUser($userId, $processParticipationId);

        if($result) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'User already vote this process participation');
        }

        return true;
    }

    public function checkIfProcessParticipationIsInVotePeriod(ProcessParticipation $processParticipation)
    {
        if (ProcessParticipationStateEnum::DEBATE !== $processParticipation->getState()) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Process participation is not in vote period');
        }

        return true;
    }

    protected function getUser()
    {
        if (null === $token = $this->securityContext->getToken()) {
            throw new HttpException(Codes::HTTP_UNAUTHORIZED, 'User not logged');
        }

        if (!is_object($user = $token->getUser())) {
            throw new HttpException(Codes::HTTP_UNAUTHORIZED, 'User not logged');
        }

        return $user;
    }
}
