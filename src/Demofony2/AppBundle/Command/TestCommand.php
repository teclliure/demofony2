<?php

namespace Demofony2\AppBundle\Command;

use Demofony2\AppBundle\Entity\Comment;
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

            $comments = $this->getDoctrine()->getRepository('Demofony2AppBundle:Comment')->findAll();

        /** @var Comment $comment */
        foreach ($comments as $comment) {

            if (!is_object($comment->getProcessParticipation()) && !is_object($comment->getProposal()) && !is_object($comment->getCitizenForum())) {
                var_dump($comment->getId());
                $this->getDoctrine()->remove($comment);
            }
          }

        $this->getDoctrine()->flush();

//        $result =  $this->getContainer()->get('app.statistics')->getVisitsByYear(new \DateTime('-1 years'));
//        var_dump($result);

        $output->writeln('<info>------ TEST DONE ------</info>');
    }

    public function getDoctrine()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }
}
