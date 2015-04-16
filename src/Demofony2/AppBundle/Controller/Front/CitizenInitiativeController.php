<?php

namespace Demofony2\AppBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * CitizenInitiativeController
 */
class CitizenInitiativeController extends Controller
{
    /**
     * @Route("/citizen-initiative/", name="demofony2_front_citizen_initiative_list")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $initiatives = $this->getDoctrine()->getManager()->getRepository('Demofony2AppBundle:CitizenInitiative')->findAll();

        return $this->render(':Front/citizenInitiative:list.html.twig', array(
            'initiatives' => $initiatives
        ));
    }
}
