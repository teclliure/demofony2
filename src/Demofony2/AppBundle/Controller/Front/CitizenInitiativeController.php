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
    const ITEMS_BY_PAGE = 5;

    /**
     * @Route("/citizen-initiative/{open}", name="demofony2_front_citizen_initiative_list")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request, $open =1)
    {
        $this->getTabActive($request);
        $paginator  = $this->get('knp_paginator');
        $openQueryBuilder = $this->getDoctrine()->getManager()->getRepository('Demofony2AppBundle:CitizenInitiative')->getOpenQueryBuilder();
        $closedQueryBuilder = $this->getDoctrine()->getManager()->getRepository('Demofony2AppBundle:CitizenInitiative')->getOpenQueryBuilder();
        $openPage = isset($openPage) ? $openPage : $request->query->get('open', $open);
        $closedPage = isset($closedPage) ? $closedPage : $request->query->get('closed', 1);

        $openInitiatives = $paginator->paginate(
            $openQueryBuilder,
            $openPage,
            self::ITEMS_BY_PAGE,
            array(
                'pageParameterName' => 'open',
            )
        );

        $closedInitiatives = $paginator->paginate(
            $closedQueryBuilder,
            $closedPage,
            self::ITEMS_BY_PAGE,
            array(
                'pageParameterName' => 'closed',
            )
        );

        return $this->render(':Front/citizenInitiative:list.html.twig', array(
            'openInitiatives' => $openInitiatives,
            'closedInitiatives' => $closedInitiatives,
        ));
    }

    private function getTabActive(Request $request)
    {
        $refererUrl = $request->headers->get('referer');
        ld(explode('?', $refererUrl));
    }
}
