<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

class ProposalControllerVotesTest extends AbstractDemofony2ControllerTest
{
    const PROPOSAL_ID = 1;
    const ANSWER_ID = 1;

    const USER1 = 'user1';
    const USER_PASSWORD1 = 'user1';

    const USER2 = 'user2';
    const USER_PASSWORD2 = 'user2';

    const USER3 = 'user3';
    const USER_PASSWORD3 = 'user3';

    /**
     * add a vote
     * test vote twice
     * test vote count
     * test delete vote not from owner
     * test delete vote
     * test edit vote
     */
    public function testVotesSystem()
    {
        //test not logged
        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(401);

        //login user1
        $this->initialize(self::USER1, self::USER_PASSWORD1);

        //test proposal answer not belongs to proposal
        $url = $this->getDemofony2Url(1, 2);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(400);

        //test proposal in closed state
        $url = $this->getDemofony2Url(2, 6);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(400);

        //first_vote
        $url = $this->getDemofony2Url(1, 1);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('votes_count', $response);
        $this->assertEquals(1, $response['votes_count']);

        //400 because can't vote two times
        $url = $this->getDemofony2Url(1, 1);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(400);

        //login user2
        $this->initialize(self::USER2, self::USER_PASSWORD2);

        //second_vote
        $url = $this->getDemofony2Url(1, 1);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response['vote']);
        $this->assertArrayHasKey('votes_count', $response);
        $this->assertEquals(2, $response['votes_count']);

             //vote deleted
        $url = $this->getDeleteVoteUrl(1, 1);
        $response = $this->request($this->getValidParameters(), $url, 'DELETE');
        $this->assertStatusResponse(204);

        // 400 because user does not have a vote
        $url = $this->getDeleteVoteUrl(1, 1);
        $response = $this->request($this->getValidParameters(), $url, 'DELETE');
        $this->assertStatusResponse(400);

        //now we can vote again
        $url = $this->getDemofony2Url(1, 1);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response['vote']);
        $this->assertArrayHasKey('votes_count', $response);
        $this->assertEquals(2, $response['votes_count']);
        $voteId = $response['vote']['id'];

        //test edit
        $url = $this->getPutVoteUrl(1, 1);
        $response = $this->request($this->getValidParameters(), $url, 'PUT');
        $this->assertStatusResponse(204);

        //test count votes in get proposals
        $url = $this->getProposalsUrl(1);
        $response = $this->request([], $url, 'GET');
        $this->assertStatusResponse(200);
        $this->assertEquals(2, $response['total_votes_count']);
        $this->assertTrue($response['proposal_answers'][0]['user_has_vote_this_proposal_answer']);
        $this->assertEquals(2, $response['proposal_answers'][0]['votes_count']);
        $this->assertTrue($response['user_already_vote']);

        //login user3
        $this->initialize(self::USER3, self::USER_PASSWORD3);

        //user 3 not voted this proposal_answer
        $response = $this->request([], $url, 'GET');
        $this->assertStatusResponse(200);
        $this->assertEquals(2, $response['total_votes_count']);
        $this->assertFalse($response['proposal_answers'][0]['user_has_vote_this_proposal_answer']);
        $this->assertFalse($response['user_already_vote']);
    }

    public function getMethod()
    {
        return 'POST';
    }

    public function getDemofony2Url($ppId = self::PROPOSAL_ID, $answerId = self::ANSWER_ID)
    {
        return self::API_VERSION.'/proposals/'.$ppId.'/answers/'.$answerId.'/vote';
    }

    public function getDeleteVoteUrl($ppId, $answerId)
    {
        return self::API_VERSION.'/proposals/'.$ppId.'/answers/'.$answerId.'/vote';
    }

    public function getPutVoteUrl($ppId, $answerId)
    {
        return self::API_VERSION.'/proposals/'.$ppId.'/answers/'.$answerId.'/vote';
    }

    public function getProposalsUrl($ppId)
    {
        return self::API_VERSION.'/proposals/'.$ppId;
    }

    public function getValidParameters()
    {
        return array(
                'comment' => 'comment vote',
        );
    }

    public function getRequiredParameters()
    {
    }
}
