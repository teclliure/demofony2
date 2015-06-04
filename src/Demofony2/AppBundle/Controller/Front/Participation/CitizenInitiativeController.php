<?php

namespace Demofony2\AppBundle\Controller\Front\Participation;

use Demofony2\AppBundle\Entity\CitizenInitiative;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * CitizenInitiativeController.
 */
class CitizenInitiativeController extends Controller
{
    const ITEMS_BY_PAGE = 10;

    /**
     * @param int $open
     * @param int $closed
     * @Route("/participation/citizen-initiative/open{open}/", name="demofony2_front_participation_citizen_initiative_list_open")
     * @Route("/participation/citizen-initiative/closed{closed}/", name="demofony2_front_participation_citizen_initiative_list_closed")
     *
     * @return Response
     */
    public function listAction($open = 1, $closed = 1)
    {
        $openQueryBuilder = $this->getDoctrine()->getManager()->getRepository('Demofony2AppBundle:CitizenInitiative')->getOpenQueryBuilder();
        $closedQueryBuilder = $this->getDoctrine()->getManager()->getRepository('Demofony2AppBundle:CitizenInitiative')->getClosedQueryBuilder();

        $pagination = $this->get('app.pagination');
        $pagination->setItemsByPage(self::ITEMS_BY_PAGE)
            ->setFirstPaginationPage($open)
            ->setFirstPaginationQueryBuilder($openQueryBuilder)
            ->setFirstPaginationRoute('demofony2_front_participation_citizen_initiative_list_open')
            ->setSecondPaginationPage($closed)
            ->setSecondPaginationQueryBuilder($closedQueryBuilder)
            ->setSecondPaginationRoute('demofony2_front_participation_citizen_initiative_list_closed');
        list($open, $closed, $isOpenTab) = $pagination->getDoublePagination();

        return $this->render(
            ':Front/participation:citizen-initiatives.html.twig',
            array(
                'openInitiatives'   => $open,
                'closedInitiatives' => $closed,
                'open'              => $isOpenTab,
            )
        );
    }

    /**
     * @param Request           $request
     * @param CitizenInitiative $initiative
     * @Route("/participation/citizen-initiative/{id}/", name="demofony2_front_participation_citizen_initiative_detail")
     * @ParamConverter("initiative", class="Demofony2AppBundle:CitizenInitiative")
     *
     * @return Response
     */
    public function detailAction(Request $request, CitizenInitiative $initiative)
    {
        return $this->render(
            ':Front/participation:citizen-initiatives-detail.html.twig',
            array(
                'initiative' => $initiative,
            )
        );
    }
}
