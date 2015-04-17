<?php

namespace Demofony2\AppBundle\Controller\Front\Participation;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CitizenInitiativeController
 */
class CitizenInitiativeController extends Controller
{
    const ITEMS_BY_PAGE = 5;

    /**
     * @Route("/participation/citizen-initiative/open{open}/", name="demofony2_front_participation_citizen_initiative_list_open")
     * @Route("/participation/citizen-initiative/closed{closed}/", name="demofony2_front_participation_citizen_initiative_list_closed")
     *
     * @param Request $request
     * @param int     $open
     * @param int     $closed
     *
     * @return Response
     */
    public function listAction(Request $request, $open = 1, $closed = 1)
    {
        $isOpenTab = true;

        if ('demofony2_front_participation_citizen_initiative_list_closed' === $request->get('_route')) {
            $isOpenTab = false;
        }

        $openQueryBuilder = $this->getDoctrine()->getManager()->getRepository('Demofony2AppBundle:CitizenInitiative')->getOpenQueryBuilder();
        $closedQueryBuilder = $this->getDoctrine()->getManager()->getRepository('Demofony2AppBundle:CitizenInitiative')->getOpenQueryBuilder();
        $openInitiatives = $this->get('app.pagination')->getPagination($openQueryBuilder, $open, self::ITEMS_BY_PAGE, 'open', 'demofony2_front_participation_citizen_initiative_list_open');
        $closedInitiatives = $this->get('app.pagination')->getPagination($closedQueryBuilder, $closed, self::ITEMS_BY_PAGE, 'closed', 'demofony2_front_participation_citizen_initiative_list_closed');

        return $this->render(':Front/participation:citizen-initiatives.html.twig', array(
            'openInitiatives' => $openInitiatives,
            'closedInitiatives' => $closedInitiatives,
            'open' => $isOpenTab,
        ));
    }
}
