<?php

namespace Demofony2\AppBundle\Tests\Api\Controller;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Wouzee\ApiRestBundle\Tests\Controller\WouzeeControllerTest;
use Wouzee\ApiRestBundle\Util\ErrorCodes;

class ProcessParticipationControllerPostCommentsTest extends AbstractDemofony2ControllerTest
{
    const PROCESSPARTICIPATION_ID = 1;

    public function testExceptionNotLogged()
    {
        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(401);
    }

    public function testInPresentationPeriodLogged()
    {
        $this->initialize('admin', 'admin');
        $url = $this->getDemofony2Url(1);
        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(500);
    }

    public function testInDebatePeriodLogged()
    {
        $this->initialize('admin', 'admin');
        $url = $this->getDemofony2Url(2);
        $response = $this->request($this->getValidParameters());
        $this->assertStatusResponse(201);
    }

    public function getMethod()
    {
        return 'POST';
    }

    public function getDemofony2Url($ppId = self::PROCESSPARTICIPATION_ID)
    {
        return self::API_VERSION . '/processparticipations/' . $ppId . '/comments';
    }


    public function getValidParameters()
    {
        return array(
            'comment' => array(
                'title' => 'test',
                'comment' => 'test'
            )
        );
    }

    public function getRequiredParameters()
    {

    }

}
