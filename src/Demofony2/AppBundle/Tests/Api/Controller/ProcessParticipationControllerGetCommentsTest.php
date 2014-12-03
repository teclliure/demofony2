<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

class ProcessParticipationControllerGetCommentsTest extends AbstractDemofony2ControllerTest
{
    const PROCESSPARTICIPATION_ID = 1;

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

    public function getDemofony2Url($ppId = self::PROCESSPARTICIPATION_ID)
    {
        return self::API_VERSION.'/processparticipations/'.$ppId.'/comments';
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
