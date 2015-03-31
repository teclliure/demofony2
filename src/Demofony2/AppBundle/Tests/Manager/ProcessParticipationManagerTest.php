<?php

namespace Demofmony2\AppBundle\Tests\Manager;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;
use Demofony2\AppBundle\Manager\ProcessParticipationManager;
use Demofony2\AppBundle\Entity\ProcessParticipation;

class ProcessParticipationManagerTest extends WebTestCase
{
    /**
     * @var ProcessParticipationManager
     */
    public $pp;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->pp = $kernel->getContainer()->get('app.process_participation');
    }

    public function testWhenStateIsPresentation()
    {
        $pp = new ProcessParticipation();
        $date = new \DateTime();
        $date->modify('+1 days');
        $pp->setDebateAt($date);

        $date = new \DateTime();
        $date->modify('+1 days');
        $pp->setFinishAt($date);

        $state = $this->pp->getAutomaticState($pp);
        $this->assertEquals(ProcessParticipationStateEnum::PRESENTATION, $state);
    }

    public function testWhenStateIsDebate()
    {
        $pp = new ProcessParticipation();
        $date = new \DateTime();
        $date->modify('-1 hours');
        $pp->setDebateAt($date);

        $date = new \DateTime();
        $date->modify('+1 days');
        $pp->setFinishAt($date);

        $state = $this->pp->getAutomaticState($pp);
        $this->assertEquals(ProcessParticipationStateEnum::DEBATE, $state);
    }

    public function testWhenStateIsClosed()
    {
        $pp = new ProcessParticipation();
        $date = new \DateTime();
        $date->modify('-1 days');
        $pp->setDebateAt($date);

        $date = new \DateTime();
        $date->modify('-1 hours');
        $pp->setFinishAt($date);

        $state = $this->pp->getAutomaticState($pp);
        $this->assertEquals(ProcessParticipationStateEnum::CLOSED, $state);
    }
}
