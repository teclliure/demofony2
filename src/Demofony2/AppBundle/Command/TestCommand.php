<?php

namespace Demofony2\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('demofony2:test')
            ->setDescription('Some Test')
;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>------ STARTING TEST ------</info>');

//       $result =  $this->getContainer()->get('app.statistics')->getCommentsPublishedByDay();
//        var_dump($result);
//
//        $result =  $this->getContainer()->get('app.statistics')->getCommentsPublishedByMonth();
//        var_dump($result);
//
//        $result = $this->getContainer()->get('app.statistics')->getCommentsPublishedByYear();
//        var_dump($result);
//
//
//        $result = $this->getContainer()->get('app.statistics')->getProposalsByDay();
//        var_dump($result);
//        $result = $this->getContainer()->get('app.statistics')->getProposalsByMonth();
//        var_dump($result);
//        $result = $this->getContainer()->get('app.statistics')->getProposalsByYear();
//        var_dump($result);
//
//        $result = $this->getContainer()->get('app.statistics')->getVotesByDay();
//        var_dump($result);
//        $result = $this->getContainer()->get('app.statistics')->getVotesByMonth();
//        var_dump($result);
//        $result = $this->getContainer()->get('app.statistics')->getVotesByYear();
//        var_dump($result);
//
//        $result = $this->getContainer()->get('app.statistics')->getIndexParticipationByDay();
//        var_dump($result);
//
//        $result = $this->getContainer()->get('app.statistics')->getIndexParticipationByMonth();
//        var_dump($result);
//
//        $result = $this->getContainer()->get('app.statistics')->getIndexParticipationByYear();
//        var_dump($result);

        $result =  $this->getContainer()->get('app.statistics')->getVisitsByYear(new \DateTime('-1 years'));
        var_dump($result);



        $output->writeln('<info>------ TEST DONE ------</info>');
    }

    public function getDoctrine()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }
}
