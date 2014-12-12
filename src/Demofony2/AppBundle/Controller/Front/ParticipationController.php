<?php

namespace Demofony2\AppBundle\Controller\Front;

use Demofony2\AppBundle\Entity\ProcessParticipation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class ParticipationController
 *
 * @category Controller
 * @package  Demofony2\AppBundle\Controller\Front
 * @author   David Romaní <david@flux.cat>
 */
class ParticipationController extends Controller
{
    /**
     * @Route("/participation/", name="demofony2_front_participation")
     */
    public function participationAction()
    {
        return $this->render('Front/participation.html.twig');
    }

    /**
     * @Route("/participation/calendar/", name="demofony2_front_participation_calendar")
     */
    public function participationCalendarAction()
    {
        return $this->render('Front/participation/calendar.html.twig');
    }

    /**
     * @Route("/participation/discussions/", name="demofony2_front_participation_discussions")
     */
    public function participationDiscussionsAction()
    {
        return $this->render('Front/participation/discussions.html.twig');
    }

    /**
     * @Route("/participation/discussions/{id}/{discussion}/", name="demofony2_front_participation_discussions_edit")
     * @ParamConverter("discussion", class="Demofony2UserBundle:ProcessParticipation")
     */
    public function participationDiscussionsEditAction(ProcessParticipation $discussion)
    {
        return $this->render('Front/participation/discussions.edit.html.twig', array('discussion' => $discussion));
    }

    /**
     * @Route("/participation/porposals/", name="demofony2_front_participation_proposals")
     */
    public function participationProposalsAction()
    {
        return $this->render('Front/participation/proposals.html.twig');
    }

    /**
     * @Route("/participation/porposals/add-new-proposal/", name="demofony2_front_participation_proposals_new")
     */
    public function participationProposalsNewAction()
    {
        return $this->render('Front/participation/proposals.new.html.twig');
    }
}
