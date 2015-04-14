<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

class CitizenForumControllerGetCommentsChildrensTest extends AbstractDemofony2ControllerTest
{
    const CITIZENFORUM_ID = 1;
    const COMMENT_ID = 1;

    public function testGetCommentsCorrect()
    {
        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(200);
        $this->assertArrayHasKey('comments', $response);
        $this->assertArrayHasKey('count', $response);
    }

    public function getMethod()
    {
        return 'GET';
    }

    public function getDemofony2Url($ppId = self::CITIZENFORUM_ID, $commentID = self::COMMENT_ID)
    {
        return self::API_VERSION.'/citizenforums/'.$ppId.'/comments/'.$commentID.'/childrens';
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
