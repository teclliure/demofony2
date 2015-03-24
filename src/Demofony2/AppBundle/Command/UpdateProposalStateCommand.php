<?php

namespace Demofony2\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Demofony2\AppBundle\Enum\ProposalStateEnum;
use Symfony\Component\Console\Input\InputOption;

class UpdateProposalStateCommand extends ContainerAwareCommand
{
    private $batchWindow = 25; // flush & clear iteration trigger

    protected function configure()
    {
        $this
            ->setName('demofony2:proposal:update-state')
            ->setDescription('Update proposal state when automaticState flag is true')
            ->addOption('force', null, InputOption::VALUE_NONE, 'If set, the task will save updates to database')
            ->addOption(
                     'show-data',
                     null,
                     InputOption::VALUE_NONE,
                     'If set, it shows loaded data in real time (low performace)'
                 );
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln('<info>------ STARTING COMMAND ------</info>');
        $em  = $this->getDoctrine();
        /** @var  $proposalManager \Demofony2\AppBundle\Manager\ProposalManager */
        $proposalManager = $this->getProposalManager();

        $results = $em->getRepository('Demofony2AppBundle:Proposal')->queryAllToUpdateState()->iterate();

        $index=0;

        while (false !== ($pp = $results->next())) {

            /** @var  $proposal \Demofony2\AppBundle\Entity\Proposal */
            $proposal = $pp[0];
            $oldState = $proposal->getState();
            $newState = $proposalManager->getAutomaticState($proposal);
            $proposal->setState($newState);

            if ($input->getOption('show-data')) {
                $output->writeln(
                    '<info>Proposal with id  ' . $proposal->getId(
                    ) . ' has been changed state, old state: ' . ProposalStateEnum::getTranslations(
                    )[$oldState] . ' new state: ' . ProposalStateEnum::getTranslations(
                    )[$newState] . '</info>'
                );
            }

            if ($index % $this->batchWindow == 0 && $input->getOption('force')) {
                $em->flush();
                $em->clear();
            }

            $index++;
        }


        if ($input->getOption('force')) {
            $em->flush();
            $em->clear();
        }

        $output->writeln('<info>------ COMMAND DONE DONE ------</info>');
    }

    public function getDoctrine()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    public function getProposalManager()
    {
        return $this->getContainer()->get('app.proposal');
    }
}
