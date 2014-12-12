<?php

namespace Demofony2\AppBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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

        return $this->render('Front/homepage.html.twig', array(
                'levels' => $levels,
            ));
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
     * @Route("/profile/{id}/{username}/", name="demofony2_front_profile")
     */
    public function profileAction($id, $username)
    {
        return $this->render('Front/profile.html.twig', array('user' => $username));
    }
}
