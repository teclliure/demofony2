<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Wouzee\ApiRestBundle\Tests\Controller\WouzeeControllerTest;
use Wouzee\ApiRestBundle\Util\ErrorCodes;

class ProcessParticipationControllerPutCommentsTest extends AbstractDemofony2ControllerTest
{
    const PROCESSPARTICIPATION_ID = 1;
    const COMMENT_ID = 1;

    const USER = 'user1';
    const USER_PASSWORD = 'user1';

    public function testExceptionNotLogged()
    {
        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(401);
    }

    public function testUserIsNotTheOwnerOfComment()
    {
        $this->initialize(self::USER, self::USER_PASSWORD);
        $url = $this->getDemofony2Url(1,2);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(403);
        $this->assertEquals('Forbidden', $response['error']['message']);
    }

    public function testInPresentationState()
    {
        $this->initialize(self::USER, self::USER_PASSWORD);
        $url = $this->getDemofony2Url(1,1);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(400);
        $this->assertEquals('Bad Request', $response['error']['message']);
    }

    public function testCommentNotBelongsToProcessParticipation()
    {
        $this->initialize(self::USER, self::USER_PASSWORD);
        $url = $this->getDemofony2Url(1,4);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(400);
        $this->assertEquals('Bad Request', $response['error']['message']);
    }

    public function testOk()
    {
        $this->initialize(self::USER, self::USER_PASSWORD);
        $url = $this->getDemofony2Url(2,4);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(204);
    }

//    public function testUserIsNotTheOwnerOfComment2()
//    {
//        $this->initialize(self::USER, self::USER_PASSWORD);
//        $url = $this->getDemofony2Url(1,1);
//        $response = $this->request($this->getValidParameters());
//        $this->assertStatusResponse(403);
//        var_dump($response['error']);
//    }
//
//    public function testInDebatePeriodLogged()
//    {
//        $this->initialize(self::USER, self::USER_PASSWORD);
//        $url = $this->getDemofony2Url(2);
//        $response = $this->request($this->getValidParameters(), $url);
//        $this->assertStatusResponse(201);
//        $this->assertArrayHasKey('id', $response);
//        $this->assertArrayHasKey('author', $response);
//        $this->assertEquals(self::USER, $response['author']['username']);
//    }
//
//
//    public function testInClosedPeriodLogged()
//    {
//        $this->initialize(self::USER, self::USER_PASSWORD);
//        $url = $this->getDemofony2Url(5);
//        $response = $this->request($this->getValidParameters(), $url);
//        $this->assertStatusResponse(500);
//    }

    public function getMethod()
    {
        return 'PUT';
    }

    public function getDemofony2Url($ppId = self::PROCESSPARTICIPATION_ID, $commentId = self::COMMENT_ID)
    {
        return self::API_VERSION . '/processparticipations/' . $ppId . '/comments/' . $commentId;
    }


    public function getValidParameters()
    {
        return array(
            'comment' => array(
                'title' => 'title-edited',
                'comment' => 'comment-edited'
            )
        );
    }

    public function getRequiredParameters()
    {

    }

}
