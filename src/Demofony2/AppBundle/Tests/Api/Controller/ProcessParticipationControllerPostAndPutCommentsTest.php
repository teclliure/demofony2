<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

use Liip\FunctionalTestBundle\Annotations\QueryCount;

class ProcessParticipationControllerPostAndPutCommentsTest extends AbstractDemofony2ControllerTest
{
    //moderated
    const PROCESSPARTICIPATION_ID1 = 1;

    //not moderated
    const PROCESSPARTICIPATION_ID2 = 2;

    const USER1 = 'user1';
    const USER_PASSWORD1 = 'user1';

    const USER2 = 'user2';
    const USER_PASSWORD2 = 'user2';

    /**
     * test create comment
     * test edit comment
     * test comment not belongs to process participation
     * test user is not owner
     * @QueryCount(100)
     */
    public function testInDebatePeriodLogged()
    {
        //test not logged
        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(401);

        //test in presentation period with user1 logged
        $this->initialize(self::USER1, self::USER_PASSWORD1);
        $url = $this->getDemofony2Url(1);
        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(500);

        //test in closed  period with user 1 logged
        $url = $this->getDemofony2Url(5);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(500);

        //post a comment
        $this->initialize(self::USER1, self::USER_PASSWORD1);
        $url = $this->getDemofony2Url(2);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('author', $response);
        $this->assertEquals(self::USER1, $response['author']['username']);
        $this->assertEquals(self::USER1, $response['author']['image_url']);
        $commentId = $response['id'];

        //test edit
        $url = $this->getEditUrl(2, $commentId);
        $response = $this->request($this->getValidParameters(), $url, 'PUT');
        $this->assertStatusResponse(204);

        //test comment not belongs to process particiaption
        $url = $this->getEditUrl(1, $commentId);
        $response = $this->request($this->getValidParameters(), $url, 'PUT');
        $this->assertStatusResponse(400);

        //login user2
        $this->initialize(self::USER2, self::USER_PASSWORD2);

        //test user not owner
        $url = $this->getEditUrl(1, $commentId);
        $response = $this->request($this->getValidParameters(), $url, 'PUT');
        $this->assertStatusResponse(403);

        //test get comments
        $url = $this->getDemofony2Url(2);
        $response = $this->request($this->getValidParameters(), $url, 'GET');
        $this->assertStatusResponse(200);
        $this->assertEquals(2, $response['count']);
        $this->assertCount(2, $response['comments']);


        //post in process participation 3 that is moderated
        $this->initialize(self::USER1, self::USER_PASSWORD1);
        $url = $this->getDemofony2Url(3);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('author', $response);
        $this->assertEquals(self::USER1, $response['author']['username']);

        //test get comments
        $url = $this->getDemofony2Url(3);
        $response = $this->request($this->getValidParameters(), $url, 'GET');
        $this->assertStatusResponse(200);
        //return 0 because comment is moderated
        $this->assertEquals(0, $response['count']);
        $this->assertCount(0, $response['comments']);


        //0 children
        $url = $this->getChildrenUrl(2, $commentId);
        $response = $this->request([], $url, 'GET');
        $this->assertStatusResponse(200);
        $this->assertEquals(0, $response['count']);

        //post in process participation 3 that is moderated
        $params = array(
            'comment' => array(
                'title' => 'test',
                'comment' => 'test',
                'parent' => $commentId
            ),
        );

        $this->initialize(self::USER1, self::USER_PASSWORD1);
        $url = $this->getDemofony2Url(2);
        $response = $this->request($params, $url);
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('author', $response);
        $this->assertEquals(self::USER1, $response['author']['username']);

        //1 children
        $url = $this->getChildrenUrl(2, $commentId);
        $response = $this->request([], $url, 'GET');
        $this->assertStatusResponse(200);
        $this->assertEquals(1, $response['count']);
    }

    public function getMethod()
    {
        return 'POST';
    }

    public function getDemofony2Url($ppId = self::PROCESSPARTICIPATION_ID1)
    {
        return self::API_VERSION.'/processparticipations/'.$ppId.'/comments';
    }

    public function getEditUrl($ppId = self::PROCESSPARTICIPATION_ID1, $commentId)
    {
        return self::API_VERSION.'/processparticipations/'.$ppId.'/comments/'.$commentId;
    }

    public function getChildrenUrl($ppId = self::PROCESSPARTICIPATION_ID1, $commentId)
    {
        return self::API_VERSION.'/processparticipations/'.$ppId.'/comments/'.$commentId .'/childrens';
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
