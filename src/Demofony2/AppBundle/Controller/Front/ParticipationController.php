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
        return $this->render('Front/participation.html.twig', array(
                'openDiscussions' => $this->getDoctrine()->getRepository('Demofony2AppBundle:ProcessParticipation')->get10LastOpenDiscussions(),
                'closeDiscussions' => $this->getDoctrine()->getRepository('Demofony2AppBundle:ProcessParticipation')->get10LastCloseDiscussions(),
                'openProposals' => $this->getDoctrine()->getRepository('Demofony2AppBundle:Proposal')->get10LastOpenProposals(),
                'closeProposals' => $this->getDoctrine()->getRepository('Demofony2AppBundle:Proposal')->get10LastCloseProposals(),
            ));
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
        return $this->render('Front/participation/discussions.html.twig', array(
                'openDiscussions' => $this->getDoctrine()->getRepository('Demofony2AppBundle:ProcessParticipation')->get10LastOpenDiscussions(),
                'closeDiscussions' => $this->getDoctrine()->getRepository('Demofony2AppBundle:ProcessParticipation')->get10LastCloseDiscussions(),
            ));
    }

    /**
     * @param ProcessParticipation $discussionInstance
     *
     * @Route("/participation/discussions/{id}/{discussion}/", name="demofony2_front_participation_discussions_edit")
     * @ParamConverter("discussionInstance", class="Demofony2AppBundle:ProcessParticipation", options={"repository_method" = "getWithJoins"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function participationDiscussionsEditAction(ProcessParticipation $discussionInstance)
    {
        $discussionResponse = $this->forward('Demofony2AppBundle:Api/ProcessParticipation:getProcessparticipation', array('id' => $discussionInstance->getId()), array('_format' => 'json'));
        $commentsResponse = $this->forward('Demofony2AppBundle:Api/ProcessParticipationComment:cgetProcessparticipationComments', array('id' => $discussionInstance->getId()), array('_format' => 'json'));

        return $this->render('Front/participation/discussions.edit.html.twig', array(
                'discussion'      => $discussionInstance,
                'asyncDiscussion' => $discussionResponse->getContent(),
                'asyncComments'   => $commentsResponse->getContent(),
            ));
    }

    /**
     * @Route("/participation/porposals/", name="demofony2_front_participation_proposals")
     */
    public function participationProposalsAction()
    {
        return $this->render('Front/participation/proposals.html.twig', array(
                'openProposals' => $this->getDoctrine()->getRepository('Demofony2AppBundle:Proposal')->get10LastOpenProposals(),
                'closeProposals' => $this->getDoctrine()->getRepository('Demofony2AppBundle:Proposal')->get10LastCloseProposals(),
            ));
    }

    /**
     * @Route("/participation/porposals/add-new-proposal/", name="demofony2_front_participation_proposals_new")
     */
    public function participationProposalsNewAction()
    {
        return $this->render('Front/participation/proposals.new.html.twig');
    }
}
