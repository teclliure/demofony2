<?php

namespace Demofony2\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Demofony2\AppBundle\Enum\ProcessParticipationStateEnum;
use Symfony\Component\Console\Input\InputOption;

class UpdateCitizenForumStateCommand extends ContainerAwareCommand
{
    private $batchWindow = 25; // flush & clear iteration trigger

    protected function configure()
    {
        $this
            ->setName('demofony2:citizen-forum:update-state')
            ->setDescription('Update citizen forum state when automaticState flag is true')
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
        /** @var  $cfManager \Demofony2\AppBundle\Manager\CitizenForumManager */
        $cfManager = $this->getCitizenForumManager();

        $results = $em->getRepository('Demofony2AppBundle:CitizenForum')->queryAllToUpdateState()->iterate();
        $index = 0;

        while (false !== ($cf = $results->next())) {

            /** @var  $cf \Demofony2\AppBundle\Entity\CitizenForum */
            $cf = $cf[0];
            $oldState = $cf->getState();
            $newState = $cfManager->getAutomaticState($cf);
            $cf->setState($newState);

            if ($input->getOption('show-data')) {
                $output->writeln(
                    '<info>CitizenForum with id  '.$cf->getId(
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

        $output->writeln('<info>------ COMMAND DONE ------</info>');    }

    public function getDoctrine()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    public function getCitizenForumManager()
    {
        return $this->getContainer()->get('app.citizen_forum');
    }
}
