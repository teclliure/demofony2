<?php

namespace Demofony2\AppBundle\Controller\Front\Participation;

use Demofony2\AppBundle\Entity\CitizenForum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CitizenForumController.
 *
 * @category Controller
 * @author   David Romaní <david@flux.cat>
 */
class CitizenForumController extends Controller
{
    const ITEMS_BY_PAGE = 10;

    /**
     * @param int $open
     * @param int $closed
     * @Route("/participation/citizen-forums/open/{open}/", name="demofony2_front_citizen_forums_list_open")
     * @Route("/participation/citizen-forums/closed/{closed}/", name="demofony2_front_citizen_forums_list_closed")
     *
     * @return Response
     */
    public function citizenForumsListAction($open = 1, $closed = 1)
    {
        $openQueryBuilder = $this->getDoctrine()->getRepository(
            'Demofony2AppBundle:CitizenForum'
        )->getOpenDiscussionsQueryBuilder();
        $closedQueryBuilder = $this->getDoctrine()->getRepository(
            'Demofony2AppBundle:CitizenForum'
        )->getCloseDiscussionsQueryBuilder();

        $pagination = $this->get('app.pagination');
        $pagination->setItemsByPage(self::ITEMS_BY_PAGE)
            ->setFirstPaginationPage($open)
            ->setSecondPaginationPage($closed)
            ->setSecondPaginationQueryBuilder($closedQueryBuilder)
            ->setFirstPaginationQueryBuilder($openQueryBuilder)
            ->setFirstPaginationRoute('demofony2_front_citizen_forums_list_open')
            ->setSecondPaginationRoute('demofony2_front_citizen_forums_list_closed');
        list($open, $closed, $isOpenTab) = $pagination->getDoublePagination();

        return $this->render(
            'Front/participation/citizen-forums.html.twig',
            array(
                'openCitizenForums'  => $open,
                'closeCitizenForums' => $closed,
                'open'               => $isOpenTab,
            )
        );
    }

    /**
     * @param CitizenForum $citizenForumInstance
     * @Route("/participation/citizen-forums/{id}/{slug}/", name="demofony2_front_participation_citizen_forums_edit")
     * @ParamConverter("citizenForumInstance", class="Demofony2AppBundle:CitizenForum", options={"repository_method" = "getWithJoins"})
     *
     * @return Response
     */
    public function citizenForumsEditAction(CitizenForum $citizenForumInstance)
    {
        $citizenForumResponse = $this->forward(
            'Demofony2AppBundle:Api/CitizenForum:getCitizenForum',
            array('id' => $citizenForumInstance->getId()),
            array('_format' => 'json')
        );
        $commentsResponse = $this->forward(
            'Demofony2AppBundle:Api/CitizenForumComment:cgetCitizenForumComments',
            array('id' => $citizenForumInstance->getId()),
            array('_format' => 'json')
        );

        return $this->render(
            'Front/participation/citizen-forums.edit.html.twig',
            array(
                'citizenForum'  => $citizenForumInstance,
                'asyncForums'   => $citizenForumResponse->getContent(),
                'asyncComments' => $commentsResponse->getContent(),
            )
        );
    }
}
