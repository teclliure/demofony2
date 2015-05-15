<?php

namespace Demofony2\AppBundle\Controller\Front\Participation;

use Demofony2\AppBundle\Entity\ProcessParticipation;
use Demofony2\AppBundle\Entity\ProcessParticipationPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProcessParticipationController.
 *
 * @category Controller
 *
 * @author   David Romaní <david@flux.cat>
 */
class ProcessParticipationController extends Controller
{
    const ITEMS_BY_PAGE = 10;

    /**
     * @Route("/participation/", name="demofony2_front_participation")
     *
     * @return Response
     */
    public function participationAction()
    {
        return $this->render('Front/participation.html.twig');
    }

    /**
     * @Route("/participation/calendar/", name="demofony2_front_participation_calendar")
     *
     * @return Response
     */
    public function participationCalendarAction()
    {
        return $this->render('Front/participation/calendar.html.twig');
    }

    /**
     * @param Request $request
     * @param int $closed
     * @param int $open
     * @Route("/participation/discussions/open{open}/", name="demofony2_front_participation_discussions_list_open")
     * @Route("/participation/discussions/closed{closed}/", name="demofony2_front_participation_discussions_list_closed")
     *
     * @return Response
     */
    public function participationDiscussionsListAction(Request $request, $open = 1, $closed = 1)
    {
        $openQueryBuilder = $this->getDoctrine()->getRepository(
            'Demofony2AppBundle:ProcessParticipation'
        )->getOpenDiscussionsQueryBuilder();

        $closedQueryBuilder = $this->getDoctrine()->getRepository(
            'Demofony2AppBundle:ProcessParticipation'
        )->getClosedDiscussionsQueryBuilder();

        $pagination = $this->get('app.pagination');
        $pagination->setItemsByPage(self::ITEMS_BY_PAGE)
            ->setFirstPaginationPage($open)
            ->setSecondPaginationPage($closed)
            ->setSecondPaginationQueryBuilder($closedQueryBuilder)
            ->setFirstPaginationQueryBuilder($openQueryBuilder)
            ->setFirstPaginationRoute('demofony2_front_participation_discussions_list_open')
            ->setSecondPaginationRoute('demofony2_front_participation_discussions_list_closed');
        list($open, $closed, $isOpenTab) = $pagination->getDoublePagination();

        return $this->render(
            'Front/participation/discussions.html.twig',
            array(
                'openDiscussions'  => $open,
                'closeDiscussions' => $closed,
                'open'              => $isOpenTab,
            )
        );
    }

    /**
     * @param ProcessParticipation $discussionInstance
     * @Route("/participation/discussions/{id}/{discussion}/", name="demofony2_front_participation_discussions_edit")
     * @ParamConverter("discussionInstance", class="Demofony2AppBundle:ProcessParticipation", options={"repository_method" = "getWithJoins"})
     *
     * @return Response
     */
    public function participationDiscussionsEditAction(ProcessParticipation $discussionInstance)
    {
        $discussionResponse = $this->forward(
            'Demofony2AppBundle:Api/ProcessParticipation:getProcessparticipation',
            array('id' => $discussionInstance->getId()),
            array('_format' => 'json')
        );
        $commentsResponse = $this->forward(
            'Demofony2AppBundle:Api/ProcessParticipationComment:cgetProcessparticipationComments',
            array('id' => $discussionInstance->getId()),
            array('_format' => 'json')
        );

        return $this->render(
            'Front/participation/discussions.edit.html.twig',
            array(
                'discussion'      => $discussionInstance,
                'asyncDiscussion' => $discussionResponse->getContent(),
                'asyncComments'   => $commentsResponse->getContent(),
            )
        );
    }

    /**
     * @param ProcessParticipationPage $page
     * @Route("/participation/discussions/{id}/{discussion}/page/{pid}/{page}/", name="demofony2_front_participation_discussion_show_page")
     * @ParamConverter("page", class="Demofony2AppBundle:ProcessParticipationPage", options={"id" = "pid"})
     *
     * @return Response
     */
    public function showProcessParticipationPageAction(ProcessParticipationPage $page)
    {
        return $this->render(
            'Front/participation/discussions.show-page.html.twig',
            array(
                'discussion' => $page->getProcessParticipation(),
                'page'       => $page,
            )
        );
    }
}
