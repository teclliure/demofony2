<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Wouzee\ApiRestBundle\Tests\Controller\WouzeeControllerTest;
use Wouzee\ApiRestBundle\Util\ErrorCodes;

class ProcessParticipationControllerPostVoteTest extends AbstractDemofony2ControllerTest
{
    const PROCESSPARTICIPATION_ID = 1;
    const ANSWER_ID = 1;

    const USER = 'user1';
    const USER_PASSWORD = 'user1';

    public function testExceptionNotLogged()
    {
        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(401);
    }

    public function testProposalAnswerNotBelongProcessParticipation()
    {
        $this->initialize(self::USER, self::USER_PASSWORD);
        $url = $this->getDemofony2Url(1,2);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(400);
    }

    /**
     * Not in vote period
     */
    public function testProcessParticipationInPresentationState()
    {
        $this->initialize(self::USER, self::USER_PASSWORD);
        $url = $this->getDemofony2Url(1,1);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(400);
    }

    /**
     * Not in vote period
     */
    public function testProcessParticipationInClosedState()
    {
        $this->initialize(self::USER, self::USER_PASSWORD);
        $url = $this->getDemofony2Url(5,5);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(400);
    }


    public function testOk()
    {
        $this->initialize(self::USER, self::USER_PASSWORD);
        $url = $this->getDemofony2Url(2,2);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response);
    }

    public function getMethod()
    {
        return 'POST';
    }

    public function getDemofony2Url($ppId = self::PROCESSPARTICIPATION_ID, $answerId = self::ANSWER_ID)
    {
        return self::API_VERSION . '/processparticipations/' . $ppId . '/answers/' . $answerId . '/vote';
    }


    public function getValidParameters()
    {
        return array(
            'vote' => array(
                'comment' => 'comment vote'
            )
        );
    }

    public function getRequiredParameters()
    {

    }

}
