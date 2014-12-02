<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Wouzee\ApiRestBundle\Tests\Controller\WouzeeControllerTest;
use Wouzee\ApiRestBundle\Util\ErrorCodes;

class ProcessParticipationControllerGetCommentsChildrensTest extends AbstractDemofony2ControllerTest
{

    const PROCESSPARTICIPATION_ID = 1;
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

    public function getDemofony2Url($ppId = self::PROCESSPARTICIPATION_ID, $commentID = self::COMMENT_ID)
    {
        return self::API_VERSION . '/processparticipations/' . $ppId . '/comments/' . $commentID . '/childrens' ;
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
