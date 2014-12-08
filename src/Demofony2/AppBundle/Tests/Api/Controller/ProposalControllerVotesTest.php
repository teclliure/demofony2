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

    public function testExceptionNotLogged()
    {
        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(401);
    }

    public function testProposalAnswerNotBelongProposal()
    {
        $this->initialize(self::USER1, self::USER_PASSWORD1);
        $url = $this->getDemofony2Url(1, 2);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(400);
    }

    /**
     * Not in vote period
     */
    public function testProposalInClosedState()
    {
        $this->initialize(self::USER1, self::USER_PASSWORD1);
        $url = $this->getDemofony2Url(2, 6);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(400);
    }

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
        //login user1
        $this->initialize(self::USER1, self::USER_PASSWORD1);

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

        //second_vote
        $this->initialize(self::USER2, self::USER_PASSWORD2);
        $url = $this->getDemofony2Url(1, 1);
        $response = $this->request($this->getValidParameters(), $url);
        $this->assertStatusResponse(201);
        $this->assertArrayHasKey('id', $response['vote']);
        $this->assertArrayHasKey('votes_count', $response);
        $this->assertEquals(2, $response['votes_count']);

        //login user2
        $this->initialize(self::USER2, self::USER_PASSWORD2);

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

    public function getValidParameters()
    {
        return array(
            'vote' => array(
                'comment' => 'comment vote',
            ),
        );
    }

    public function getRequiredParameters()
    {
    }
}
