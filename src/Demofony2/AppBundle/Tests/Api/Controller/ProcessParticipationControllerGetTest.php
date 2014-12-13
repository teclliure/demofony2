<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

class ProcessParticipationControllerGetTest extends AbstractDemofony2ControllerTest
{
    const PROCESSPARTICIPATION_ID = 1;

    public function testGetCommentsCorrect()
    {
        //404 because not exists
        $url = $this->getDemofony2Url(0);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(404);

        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(200);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('updated_at', $response);
        $this->assertArrayHasKey('title', $response);
        $this->assertArrayHasKey('description', $response);

        $this->assertArrayHasKey('categories', $response);
        $this->assertArrayHasKey('id', $response['categories'][0]);
        $this->assertArrayHasKey('name', $response['categories'][0]);

        $this->assertArrayHasKey('proposal_answers', $response);
        $this->assertArrayHasKey('id', $response['proposal_answers'][0]);
        $this->assertArrayHasKey('title', $response['proposal_answers'][0]);
        $this->assertArrayHasKey('votes_count', $response['proposal_answers'][0]);

        $this->assertArrayHasKey('gps', $response);
        $this->assertArrayHasKey('latitude', $response['gps']);
        $this->assertArrayHasKey('longitude', $response['gps']);

        $this->assertArrayHasKey('state', $response);
        $this->assertArrayHasKey('total_votes_count', $response);
        $this->assertArrayHasKey('presentation_at', $response);
        $this->assertArrayHasKey('debate_at', $response);
    }

    public function getMethod()
    {
        return 'GET';
    }

    public function getDemofony2Url($ppId = self::PROCESSPARTICIPATION_ID)
    {
        return self::API_VERSION.'/processparticipations/'.$ppId;
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
