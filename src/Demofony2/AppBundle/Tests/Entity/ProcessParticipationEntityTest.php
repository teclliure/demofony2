<?php

namespace Demofmony2\AppBundle\Tests\Manager;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Demofony2\AppBundle\Entity\ProcessParticipation;
use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;


class ProcessParticipationEntityTest extends WebTestCase
{

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
    }

    public function testWhenStateIsDraft()
    {
        $pp = new ProcessParticipation();
        $date = new \DateTime();
        $date->modify('+10 days');
        $pp->setPresentationAt($date);

        $state = $pp->getState();
        $this->assertEquals(ProcessParticipationStateEnum::DRAFT, $state);

    }

    public function testWhenStateIsPresentation()
    {
        $pp = new ProcessParticipation();
        $date = new \DateTime();
        $date->modify('-2 days');
        $pp->setPresentationAt($date);

        $date = new \DateTime();
        $date->modify('+1 days');
        $pp->setDebateAt($date);

        $date = new \DateTime();
        $date->modify('+1 days');
        $pp->setFinishAt($date);

        $state = $pp->getState();
        $this->assertEquals(ProcessParticipationStateEnum::PRESENTATION, $state);
    }

    public function testWhenStateIsDebate()
    {
        $pp = new ProcessParticipation();
        $date = new \DateTime();
        $date->modify('-2 days');
        $pp->setPresentationAt($date);

        $date = new \DateTime();
        $date->modify('-1 hours');
        $pp->setDebateAt($date);

        $date = new \DateTime();
        $date->modify('+1 days');
        $pp->setFinishAt($date);

        $state = $pp->getState();
        $this->assertEquals(ProcessParticipationStateEnum::DEBATE, $state);
    }

    public function testWhenStateIsClosed()
    {
        $pp = new ProcessParticipation();
        $date = new \DateTime();
        $date->modify('-2 days');
        $pp->setPresentationAt($date);

        $date = new \DateTime();
        $date->modify('-1 days');
        $pp->setDebateAt($date);

        $date = new \DateTime();
        $date->modify('-1 hours');
        $pp->setFinishAt($date);

        $state = $pp->getState();
        $this->assertEquals(ProcessParticipationStateEnum::CLOSED, $state);
    }

}
