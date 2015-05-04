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
 *
 * @author   David Romaní <david@flux.cat>
 */
class CitizenForumController extends Controller
{
    /**
     * @Route("/participation/citizen-forums/", name="demofony2_front_participation_citizen_forums")
     *
     * @return Response
     */
    public function citizenForumsListAction()
    {
        return $this->render('Front/participation/citizen-forums.html.twig', array(
                'openCitizenForums'  => $this->getDoctrine()->getRepository('Demofony2AppBundle:CitizenForum')->get10LastOpenDiscussions(),
                'closeCitizenForums' => $this->getDoctrine()->getRepository('Demofony2AppBundle:CitizenForum')->get10LastCloseDiscussions(),
            ));
    }

    /**
     * @param CitizenForum $citizenForumInstance
     *
     * @Route("/participation/citizen-forums/{id}/{slug}/", name="demofony2_front_participation_citizen_forums_edit")
     * @ParamConverter("citizenForumInstance", class="Demofony2AppBundle:CitizenForum", options={"repository_method" = "getWithJoins"})
     *
     * @return Response
     */
    public function citizenForumsEditAction(CitizenForum $citizenForumInstance)
    {
        $citizenForumResponse = $this->forward('Demofony2AppBundle:Api/CitizenForum:getCitizenForum', array('id' => $citizenForumInstance->getId()), array('_format' => 'json'));
        $commentsResponse = $this->forward('Demofony2AppBundle:Api/CitizenForumComment:cgetCitizenForumComments', array('id' => $citizenForumInstance->getId()), array('_format' => 'json'));

        return $this->render('Front/participation/citizen-forums.edit.html.twig', array(
            'citizenForum'  => $citizenForumInstance,
            'asyncForums'   => $citizenForumResponse->getContent(),
            'asyncComments' => $commentsResponse->getContent(),
        ));
    }
}
