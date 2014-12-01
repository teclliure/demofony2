<?php

namespace Demofmony2\AppBundle\Tests\Manager;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Demofony2\AppBundle\Entity\ProcessParticipation;
use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Demofony2\AppBundle\Manager\VotePermissionCheckerManager;
use Symfony\Component\Config\Tests\Loader\Validator;

class VotePermissionCheckerManagerTest extends WebTestCase
{
    public $vpc;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->vpc = $kernel->getContainer()->get('app.vote_permission_checker');

    }

    /**
     * @expectedException     Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testWhenStateClosed()
    {
      $processParticipation =   $this->getMock('\Demofony2\AppBundle\Entity\ProcessParticipation');
      $processParticipation->expects($this->once())->method('getState')->will($this->returnValue(ProcessParticipationStateEnum::CLOSED));
      $this->vpc->checkIfProcessParticipationIsInVotePeriod($processParticipation);
    }

    /**
     * @expectedException     Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testWhenStateConclusions()
    {
        $processParticipation =   $this->getMock('\Demofony2\AppBundle\Entity\ProcessParticipation');
        $processParticipation->expects($this->once())->method('getState')->will($this->returnValue(ProcessParticipationStateEnum::CONCLUSIONS));
        $this->vpc->checkIfProcessParticipationIsInVotePeriod($processParticipation);
    }

    /**
     * @expectedException     Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testWhenStateDraft()
    {
        $processParticipation =   $this->getMock('\Demofony2\AppBundle\Entity\ProcessParticipation');
        $processParticipation->expects($this->once())->method('getState')->will($this->returnValue(ProcessParticipationStateEnum::DRAFT));
        $result = $this->vpc->checkIfProcessParticipationIsInVotePeriod($processParticipation);
    }

    public function testWhenStateDebate()
    {
        $processParticipation =   $this->getMock('\Demofony2\AppBundle\Entity\ProcessParticipation');
        $processParticipation->expects($this->once())->method('getState')->will($this->returnValue(ProcessParticipationStateEnum::DEBATE));
        $result = $this->vpc->checkIfProcessParticipationIsInVotePeriod($processParticipation);
        $this->assertTrue($result);
    }

    /**
     * @expectedException     Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testWhenUserAlreadyVoteTest()
    {
        $processParticipation =   $this->getMock('\Demofony2\AppBundle\Entity\ProcessParticipation');
        $processParticipation->expects($this->once())->method('getId')->will($this->returnValue(1));

        $user =   $this->getMock('\Demofony2\UserBundle\Entity\User');
        $user->expects($this->once())->method('getId')->will($this->returnValue(1));

        // Now, mock the repository so it returns the mock of the employee
        $processParticipationRepository = $this->getMockBuilder('\Demofony2\AppBundle\Repository\ProcessParticipationRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $processParticipationRepository->expects($this->once())
            ->method('countProcessParticipationVoteByUser')
            ->will($this->returnValue(1));

        // Last, mock the EntityManager to return the mock of the repository
        $entityManager = $this->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($processParticipationRepository));

        // Last, mock the EntityManager to return the mock of the repository
        $validator = $this->getMock('\Symfony\Component\Validator\Validator\ValidatorInterface');

        $checker = new VotePermissionCheckerManager($entityManager, $validator);
        $checker->checkUserHasVoteInProcessParticipation($processParticipation, $user);
    }

    public function testWhenUserNotVoteTest()
    {
        $processParticipation =   $this->getMock('\Demofony2\AppBundle\Entity\ProcessParticipation');
        $processParticipation->expects($this->once())->method('getId')->will($this->returnValue(1));

        $user =   $this->getMock('\Demofony2\UserBundle\Entity\User');
        $user->expects($this->once())->method('getId')->will($this->returnValue(1));

        // Now, mock the repository so it returns the mock of the employee
        $processParticipationRepository = $this->getMockBuilder('\Demofony2\AppBundle\Repository\ProcessParticipationRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $processParticipationRepository->expects($this->once())
            ->method('countProcessParticipationVoteByUser')
            ->will($this->returnValue(0));

        // Last, mock the EntityManager to return the mock of the repository
        $entityManager = $this->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($processParticipationRepository));

        // Last, mock the EntityManager to return the mock of the repository
        $validator = $this->getMock('\Symfony\Component\Validator\Validator\ValidatorInterface');

        $checker = new VotePermissionCheckerManager($entityManager, $validator);
       $result =  $checker->checkUserHasVoteInProcessParticipation($processParticipation, $user);
        $this->assertTrue($result);
    }

}
