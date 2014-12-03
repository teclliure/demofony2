<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

class ProposalControllerPostAndPutCommentsTest extends AbstractDemofony2ControllerTest
{
    const PROPOSAL_ID = 1;

    const USER1 = 'user1';
    const USER_PASSWORD1 = 'user1';

    const USER2 = 'user2';
    const USER_PASSWORD2 = 'user2';

    public function testExceptionNotLogged()
    {
        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(401);
    }

    public function testInClosedPeriodLogged()
    {
        $this->initialize(self::USER1, self::USER_PASSWORD1);
        $url = $this->getDemofony2Url(2);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(400);
    }

    /**
     * test create comment
     * test edit comment
     * test comment not belongs to process participation
     * test user is not owner
     */
    public function testInDebatePeriodLogged()
    {
        $this->initialize(self::USER1, self::USER_PASSWORD1);
        $url = $this->getDemofony2Url(1);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('author', $response);
        $this->assertEquals(self::USER1, $response['author']['username']);

        $commentId = $response['id'];

        //test edit
        $url = $this->getEditUrl(1, $commentId);
        $response = $this->request($this->getValidParameters(), $url, 'PUT');
        $this->assertStatusResponse(204);

        //test comment not belongs to process particiaption
        $url = $this->getEditUrl(2, $commentId);
        $response = $this->request($this->getValidParameters(), $url, 'PUT');
        $this->assertStatusResponse(400);

        //login user2
        $this->initialize(self::USER2, self::USER_PASSWORD2);

        //test user not owner
        $url = $this->getEditUrl(1, $commentId);
        $response = $this->request($this->getValidParameters(), $url, 'PUT');
        $this->assertStatusResponse(403);
    }

    public function getMethod()
    {
        return 'POST';
    }

    public function getDemofony2Url($ppId = self::PROPOSAL_ID)
    {
        return self::API_VERSION.'/proposals/'.$ppId.'/comments';
    }

    public function getEditUrl($ppId = self::PROPOSAL_ID, $commentId)
    {
        return self::API_VERSION.'/proposals/'.$ppId.'/comments/'.$commentId;
    }

    public function getValidParameters()
    {
        return array(
            'comment' => array(
                'title' => 'test',
                'comment' => 'test',
            ),
        );
    }

    public function getRequiredParameters()
    {
    }
}
