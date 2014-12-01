<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\UserBundle\Entity\User;
use Demofony2\AppBundle\Entity\ProcessParticipation;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;
use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;

class VotePermissionCheckerManager
{
    protected $em;
    protected $validator;

    /**
     * @param ObjectManager            $em
     * @param ValidatorInterface       $validator
     */
    public function __construct(
        ObjectManager $em,
        ValidatorInterface $validator
    ) {
        $this->em = $em;
        $this->validator = $validator;
    }

    public function checkUserHasVoteInProcessParticipation(ProcessParticipation $processParticipation, User $user)
    {
        $userId = $user->getId();
        $processParticipationId = $processParticipation->getId();
        $result = (int)$this->em->getRepository(
            'Demofony2AppBundle:ProcessParticipation'
        )->countProcessParticipationVoteByUser($userId, $processParticipationId);

        if ($result) {
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
}
