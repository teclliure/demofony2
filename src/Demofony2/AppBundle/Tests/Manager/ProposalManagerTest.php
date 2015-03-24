<?php

namespace Demofmony2\AppBundle\Tests\Manager;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Demofony2\AppBundle\Enum\ProposalStateEnum;
use Demofony2\AppBundle\Manager\ProposalManager;
use Demofony2\AppBundle\Entity\Proposal;

class ProposalManagerTest extends WebTestCase
{
    /**
     * @var ProposalManager
     */
    public $proposalManager;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->proposalManager = $kernel->getContainer()->get('app.proposal');
    }

    public function testWhenStateIsDebate()
    {
        $p = new Proposal();
        $date = new \DateTime();
        $date->modify('+10 days');
        $p->setFinishAt($date);

        $state = $this->proposalManager->getAutomaticState($p);
        $this->assertEquals(ProposalStateEnum::DEBATE, $state);
    }

    public function testWhenStateIsClosed()
    {
        $p = new Proposal();
        $date = new \DateTime();
        $date->modify('-1 days');
        $p->setFinishAt($date);

        $state = $this->proposalManager->getAutomaticState($p);
        $this->assertEquals(ProposalStateEnum::CLOSED, $state);
    }
}
