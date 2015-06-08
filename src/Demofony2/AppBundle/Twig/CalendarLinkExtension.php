<?php

namespace Demofony2\AppBundle\Twig;

use Demofony2\AppBundle\Entity\CalendarEvent;
use Demofony2\AppBundle\Entity\CitizenForum;
use Demofony2\AppBundle\Entity\CitizenInitiative;
use Demofony2\AppBundle\Entity\ProcessParticipation;
use Demofony2\AppBundle\Entity\Proposal;
use Doctrine\Common\Persistence\ObjectManager;
use Demofony2\AppBundle\Manager\CalendarManager;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class CalendarLinkExtension
 */
class CalendarLinkExtension extends \Twig_Extension
{
    /**
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager, RouterInterface $router)
    {
        $this->entityManager = $objectManager;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'calendar_link',
                [$this, 'getCalendarLink']
            ),
        ];
    }


    public function getCalendarLink(CalendarEvent $calendarEvent)
    {
        $category = $calendarEvent->getCategory();

        if (!$category) {
            return '#';
        }
        $category = $category->getName();
        $id = $calendarEvent->getEntityId();

        switch (true) {
            case (CalendarManager::PROCESS_PARTICIPATION === $category):
                $route = $this->getProcessParticipationLink($id);
                break;
            case (CalendarManager::PROPOSAL === $category):
                $route = $this->getProposalLink($id);

                break;
            case (CalendarManager::CITIZEN_FORUM === $category):
                $route = $this->getCitizenForumLink($id);
                break;
            case (CalendarManager::CITIZEN_INITIATIVE === $category):
                $route = $this->getCitizenInitiativeLink($id);
                break;
            default:
                throw new \Twig_Error(
                    'Unable to find category in CalendarEvent'
                );
        }

        return $route;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_calendar_link_extension';
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    protected function getProcessParticipationLink($id)
    {
        $pp = $this->entityManager->getRepository('Demofony2AppBundle:ProcessParticipation')->find($id);

        if (!$pp instanceof ProcessParticipation) {
            throw new \Exception('Process Participation with id ' . $id . 'not exists');
        }

        $route = $this->router->generate(
            'demofony2_front_participation_discussions_edit',
            array(
                'id' => $pp->getId(),
                'discussion' => $pp->getTitleSlug()
            )
        );

        return $route;
    }

    protected function getProposalLink($id)
    {
        $p = $this->entityManager->getRepository('Demofony2AppBundle:Proposal')->find($id);

        if (!$p instanceof Proposal) {
            throw new \Exception('Proposal with id ' . $id . 'not exists');
        }

        $route = $this->router->generate(
            'demofony2_front_participation_proposals_show',
            array(
                'id' => $p->getId(),
                'titleSlug' => $p->getTitleSlug()
            )
        );

        return $route;
    }

    protected function getCitizenForumLink($id)
    {
        $citizenForum = $this->entityManager->getRepository('Demofony2AppBundle:CitizenForum')->find($id);

        if (!$citizenForum instanceof CitizenForum) {
            throw new \Exception('Citizen Forum with id ' . $id . 'not exists');
        }

        $route = $this->router->generate(
            'demofony2_front_participation_citizen_forums_edit',
            array(
                'id' => $citizenForum->getId(),
                'slug' => $citizenForum->getTitleSlug()
            )
        );

        return $route;
    }

    protected function getCitizenInitiativeLink($id)
    {
        $citizenInitiative = $this->entityManager->getRepository('Demofony2AppBundle:CitizenInitiative')->find($id);

        if (!$citizenInitiative instanceof CitizenInitiative) {
            throw new \Exception('Citizen Initiative with id ' . $id . 'not exists');
        }

        $route = $this->router->generate(
            'demofony2_front_participation_citizen_initiative_detail',
            array(
                'id' => $citizenInitiative->getId(),
            )
        );

        return $route;
    }
}
