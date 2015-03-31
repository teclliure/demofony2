<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

class ProposalControllerGetTest extends AbstractDemofony2ControllerTest
{
    const PROPOSAL_ID = 1;

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

        $this->assertArrayHasKey('gps', $response);
        $this->assertArrayHasKey('latitude', $response['gps']);
        $this->assertArrayHasKey('longitude', $response['gps']);

        $this->assertArrayHasKey('proposal_answers', $response);
        $this->assertArrayHasKey('id', $response['proposal_answers'][0]);
        $this->assertArrayHasKey('title', $response['proposal_answers'][0]);
        $this->assertArrayHasKey('votes_count', $response['proposal_answers'][0]);

        $this->assertArrayHasKey('state', $response);
        $this->assertArrayHasKey('total_votes_count', $response);

        //401 because is a draft
        $url = $this->getDemofony2Url(4);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(401);

        //200 because is admin
        $this->initialize('admin', 'admin');
        $url = $this->getDemofony2Url(4);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(200);
    }

    public function getMethod()
    {
        return 'GET';
    }

    public function getDemofony2Url($ppId = self::PROPOSAL_ID)
    {
        return self::API_VERSION.'/proposals/'.$ppId;
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
