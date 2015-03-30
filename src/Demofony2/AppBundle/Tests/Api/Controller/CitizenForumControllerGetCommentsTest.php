<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

class CitizenForumGetCommentsTest extends AbstractDemofony2ControllerTest
{
    const CITIZENFORUM_ID = 1;

    public function testGetCommentsCorrect()
    {
        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(200);
        $this->assertArrayHasKey('comments', $response);
        $this->assertArrayHasKey('count', $response);
        //because comment is moderated
        $this->assertEquals(0, $response['count']);

        $url = $this->getDemofony2Url(2);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(200);
        $this->assertArrayHasKey('comments', $response);
        $this->assertArrayHasKey('count', $response);
        //because comment is not moderated
        $this->assertEquals(1, $response['count']);
    }

    public function getMethod()
    {
        return 'GET';
    }

    public function getDemofony2Url($ppId = self::CITIZENFORUM_ID)
    {
        return self::API_VERSION.'/citizenforums/'.$ppId.'/comments';
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
