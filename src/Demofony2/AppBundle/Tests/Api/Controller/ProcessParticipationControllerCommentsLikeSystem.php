<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

class ProcessParticipationControllerCommentsLikeSystemTest extends AbstractDemofony2ControllerTest
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

    /**
     * test create comment
     * test edit comment
     * test comment not belongs to process participation
     * test user is not owner
     */
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

        //test comment not belongs to process participation
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



//        //test in closed  period with user 1 logged
//        $url = $this->getDemofony2Url(5);
//        $response = $this->request($this->getValidParameters(), $url);
//        $this->assertStatusResponse(500);
//
//        //post a comment
//        $this->initialize(self::USER1, self::USER_PASSWORD1);
//        $url = $this->getDemofony2Url(2);
//        $response = $this->request($this->getValidParameters(), $url);
//        $this->assertStatusResponse(201);
//        $this->assertArrayHasKey('id', $response);
//        $this->assertArrayHasKey('author', $response);
//        $this->assertEquals(self::USER1, $response['author']['username']);
//        $commentId = $response['id'];


    }

    public function getMethod()
    {
        return 'POST';
    }

    public function getDemofony2Url($ppId = self::PROCESSPARTICIPATION_ID1, $commentId = self::COMMENT_ID1)
    {
        return self::API_VERSION.'/processparticipations/'.$ppId.'/comments/' .$commentId.'/like';
    }

    public function getUnlikeUrl($ppId = self::PROCESSPARTICIPATION_ID1, $commentId = self::COMMENT_ID1)
    {
        return self::API_VERSION.'/processparticipations/'.$ppId.'/comments/' .$commentId.'/unlike';
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
