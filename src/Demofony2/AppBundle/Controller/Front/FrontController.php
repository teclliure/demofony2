<?php

namespace Demofony2\AppBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class FrontController
 *
 * @category Controller
 * @package  Demofony2\AppBundle\Controller\Front
 * @author   David Romaní <david@flux.cat>
 */
class FrontController extends Controller
{
    /**
     * @Route("/", name="demofony2_front_homepage")
     */
    public function homepageAction()
    {
        // fake
        $levels = array(
            'uab' => 10,
            'ita' => 20,
            'law' => 15,
        );

        return $this->render('Front/homepage.html.twig', array('levels' => $levels));
    }

    /**
     * @Route("/government/", name="demofony2_front_government")
     */
    public function governmentAction()
    {
        return $this->render('Front/government.html.twig');
    }

    /**
     * @Route("/transparency/", name="demofony2_front_transparency")
     */
    public function transparencyAction()
    {
        // fake
        $data = array(
            'lastUpdate' => new \DateTime(),
        );
        $levels = array(
            'uab' => 10,
            'ita' => 20,
            'law' => 15,
        );

        return $this->render('Front/transparency.html.twig', array('data' => $data, 'levels' => $levels));
    }

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
     */
    public function participationDiscussionsEditAction($id, $discussion)
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

    /**
     * @Route("/profile/{id}/{username}/", name="demofony2_front_profile")
     */
    public function profileAction($id, $username)
    {
        return $this->render('Front/profile.html.twig', array('user' => $username));
    }
}
