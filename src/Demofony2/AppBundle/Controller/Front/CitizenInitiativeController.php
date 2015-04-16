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
    const ITEMS_BY_PAGE = 3;

    /**
     * @Route("/citizen-initiative/open{open}/", name="demofony2_front_citizen_initiative_list_open")
     * @Route("/citizen-initiative/closed{closed}/", name="demofony2_front_citizen_initiative_list_closed")
     *
     * @param Request $request
     * @param int     $open
     * @param int     $closed
     *
     * @return Response
     */
    public function listAction(Request $request, $open = 1 , $closed = 1)
    {
        $isOpenTab = true;

        if ('demofony2_front_citizen_initiative_list_closed' === $request->get('_route')) {
            $isOpenTab = false;
        }

        $paginator  = $this->get('knp_paginator');
        $openQueryBuilder = $this->getDoctrine()->getManager()->getRepository('Demofony2AppBundle:CitizenInitiative')->getOpenQueryBuilder();
        $closedQueryBuilder = $this->getDoctrine()->getManager()->getRepository('Demofony2AppBundle:CitizenInitiative')->getOpenQueryBuilder();
        $openPage = isset($openPage) ? $openPage : $request->query->get('open', $open);
        $closedPage = isset($closedPage) ? $closedPage : $request->query->get('closed', $closed);

        $openInitiatives = $paginator->paginate(
            $openQueryBuilder,
            $openPage,
            self::ITEMS_BY_PAGE,
            array(
                'pageParameterName' => 'open',
            )
        );
        $openInitiatives->setUsedRoute('demofony2_front_citizen_initiative_list_open');

        $closedInitiatives = $paginator->paginate(
            $closedQueryBuilder,
            $closedPage,
            self::ITEMS_BY_PAGE,
            array(
                'pageParameterName' => 'closed',
            )
        );
        $closedInitiatives->setUsedRoute('demofony2_front_citizen_initiative_list_closed');


        return $this->render(':Front/citizenInitiative:list.html.twig', array(
            'openInitiatives' => $openInitiatives,
            'closedInitiatives' => $closedInitiatives,
            'open' => $isOpenTab
        ));
    }
}
