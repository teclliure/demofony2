<?php

namespace Demofony2\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;
use Symfony\Component\Console\Input\InputOption;

class UpdateProcessParticipationStateCommand extends ContainerAwareCommand
{
    private $batchWindow = 25; // flush & clear iteration trigger

    protected function configure()
    {
        $this
            ->setName('demofony2:process-participation:update-state')
            ->setDescription('Update process participation state when automaticState flag is true')
            ->addOption('force', null, InputOption::VALUE_NONE, 'If set, the task will save updates to database')
            ->addOption(
                     'show-data',
                     null,
                     InputOption::VALUE_NONE,
                     'If set, it shows loaded data in real time (low performace)'
                 );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>------ STARTING COMMAND ------</info>');
        $em  = $this->getDoctrine();
        /** @var  $ppManager \Demofony2\AppBundle\Manager\ProcessParticipationManager */
        $ppManager = $this->getProcessParticipationManager();

        $results = $em->getRepository('Demofony2AppBundle:ProcessParticipation')->queryAllToUpdateState()->iterate();
        $index = 0;

        while (false !== ($pp = $results->next())) {

            /** @var  $pp \Demofony2\AppBundle\Entity\ProcessParticipation */
            $pp = $pp[0];
            $oldState = $pp->getState();
            $newState = $ppManager->getAutomaticState($pp);
            $pp->setState($newState);

            if ($input->getOption('show-data')) {
                $output->writeln(
                    '<info>ProcessParticipation with id  '.$pp->getId(
                    ).' has been changed state, old state: '.ProcessParticipationStateEnum::getTranslations(
                    )[$oldState].' new state: '.ProcessParticipationStateEnum::getTranslations(
                    )[$newState].'</info>'
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

    public function getProcessParticipationManager()
    {
        return $this->getContainer()->get('app.process_participation');
    }
}
