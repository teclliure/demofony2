<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

class ProcessParticipationControllerCommentsLikeSystem extends AbstractDemofony2ControllerTest
{
    //moderated
    const PROCESSPARTICIPATION_ID1 = 1;

    //not moderated
    const PROCESSPARTICIPATION_ID2 = 2;

    const USER1 = 'user1';
    const USER_PASSWORD1 = 'user1';

    const USER2 = 'user2';
    const USER_PASSWORD2 = 'user2';

    const COMMENT_ID1 = 1;
    const COMMENT_ID2 = 2;
    const COMMENT_ID4 = 4;

    public function testCommentVoteSystem()
    {
        //test not logged
        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(401);

        //test in presentation period with user1 logged
        $this->initialize(self::USER1, self::USER_PASSWORD1);

        //test process participation not in vote period
        $url = $this->getDemofony2Url(self::PROCESSPARTICIPATION_ID1, self::COMMENT_ID4);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(400);

        //test comment not belongs to process participation
        $url = $this->getDemofony2Url(self::PROCESSPARTICIPATION_ID2, self::COMMENT_ID1);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(400);

        //test like ok
        $url = $this->getDemofony2Url(self::PROCESSPARTICIPATION_ID2, self::COMMENT_ID4);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('comment', $response);
        $this->assertEquals(1, $response['likes_count']);
        $this->assertEquals(0, $response['unlikes_count']);
        $this->assertTrue($response['user_already_like']);
        $this->assertFalse($response['user_already_unlike']);

        //test like again twice
        $url = $this->getDemofony2Url(self::PROCESSPARTICIPATION_ID2, self::COMMENT_ID4);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(500);

        //test unlike when already like
        $url = $this->getUnlikeUrl(self::PROCESSPARTICIPATION_ID2, self::COMMENT_ID4);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(500);

        //test delete unlike when not unlike
        $url = $this->getUnlikeUrl(self::PROCESSPARTICIPATION_ID2, self::COMMENT_ID4);
        $response = $this->request($this->getValidParameters(), $url, 'DELETE');
        $this->assertStatusResponse(400);

        //test delete like ok
        $url = $this->getDemofony2Url(self::PROCESSPARTICIPATION_ID2, self::COMMENT_ID4);
        $response = $this->request($this->getValidParameters(), $url, 'DELETE');
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('comment', $response);
        $this->assertEquals(0, $response['likes_count']);
        $this->assertEquals(0, $response['unlikes_count']);
        $this->assertFalse($response['user_already_like']);
        $this->assertFalse($response['user_already_unlike']);

        //test like ok
        $url = $this->getDemofony2Url(self::PROCESSPARTICIPATION_ID2, self::COMMENT_ID4);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('comment', $response);
        $this->assertEquals(1, $response['likes_count']);
        $this->assertEquals(0, $response['unlikes_count']);
        $this->assertTrue($response['user_already_like']);
        $this->assertFalse($response['user_already_unlike']);

        //login user2
        $this->initialize(self::USER2, self::USER_PASSWORD2);

        //test other user vote to test count
        $url = $this->getDemofony2Url(self::PROCESSPARTICIPATION_ID2, self::COMMENT_ID4);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('comment', $response);
        $this->assertEquals(2, $response['likes_count']);
        $this->assertEquals(0, $response['unlikes_count']);
        $this->assertTrue($response['user_already_like']);
        $this->assertFalse($response['user_already_unlike']);

        //test delete
        $url = $this->getDemofony2Url(self::PROCESSPARTICIPATION_ID2, self::COMMENT_ID4);
        $response = $this->request($this->getValidParameters(), $url, 'DELETE');
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('comment', $response);
        $this->assertEquals(1, $response['likes_count']);
        $this->assertEquals(0, $response['unlikes_count']);
        $this->assertFalse($response['user_already_like']);
        $this->assertFalse($response['user_already_unlike']);

        //test count
        $url = $this->getUnlikeUrl(self::PROCESSPARTICIPATION_ID2, self::COMMENT_ID4);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('comment', $response);
        $this->assertEquals(1, $response['likes_count']);
        $this->assertEquals(1, $response['unlikes_count']);
        $this->assertFalse($response['user_already_like']);
        $this->assertTrue($response['user_already_unlike']);
    }

    public function getMethod()
    {
        return 'POST';
    }

    public function getDemofony2Url($ppId = self::PROCESSPARTICIPATION_ID1, $commentId = self::COMMENT_ID1)
    {
        return self::API_VERSION.'/processparticipations/'.$ppId.'/comments/'.$commentId.'/like';
    }

    public function getUnlikeUrl($ppId = self::PROCESSPARTICIPATION_ID1, $commentId = self::COMMENT_ID1)
    {
        return self::API_VERSION.'/processparticipations/'.$ppId.'/comments/'.$commentId.'/unlike';
    }

    public function getValidParameters()
    {
        return array(

        );
    }

    public function getRequiredParameters()
    {
    }
}
