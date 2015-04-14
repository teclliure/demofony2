<?php

namespace Demofony2\AppBundle\Controller\Front\Participation;

use Demofony2\AppBundle\Entity\ProcessParticipation;
use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\AppBundle\Form\Type\Front\ProposalFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CitizenControllerController
 *
 * @category Controller
 * @package  Demofony2\AppBundle\Controller\Front|Participation
 * @author   David Romaní <david@flux.cat>
 */
class CitizenControllerController extends Controller
{
    /**
     * @Route("/participation/citizen-forums/", name="demofony2_front_participation_citizen_forums")
     */
    public function citizenForumsListAction()
    {
        return $this->render('Front/participation/citizen-forums.html.twig', array(
                'openDiscussions' => $this->getDoctrine()->getRepository('Demofony2AppBundle:CitizenForum')->get10LastOpenDiscussions(),
                'closeDiscussions' => $this->getDoctrine()->getRepository('Demofony2AppBundle:CitizenForum')->get10LastCloseDiscussions(),
            ));
    }

    /**
     * @param ProcessParticipation $discussionInstance
     *
     * @Route("/participation/discussions/{id}/{discussion}/", name="demofony2_front_participation_discussions_edit")
     * @ParamConverter("discussionInstance", class="Demofony2AppBundle:ProcessParticipation", options={"repository_method" = "getWithJoins"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
//    public function participationDiscussionsEditAction(ProcessParticipation $discussionInstance)
//    {
//        $discussionResponse = $this->forward('Demofony2AppBundle:Api/ProcessParticipation:getProcessparticipation', array('id' => $discussionInstance->getId()), array('_format' => 'json'));
//        $commentsResponse = $this->forward('Demofony2AppBundle:Api/ProcessParticipationComment:cgetProcessparticipationComments', array('id' => $discussionInstance->getId()), array('_format' => 'json'));
//
//        return $this->render('Front/participation/discussions.edit.html.twig', array(
//                'discussion'      => $discussionInstance,
//                'asyncDiscussion' => $discussionResponse->getContent(),
//                'asyncComments'   => $commentsResponse->getContent(),
//            ));
//    }
}
