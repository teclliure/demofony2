<?php

namespace Demofony2\AppBundle\Controller\Front;

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
 * Class ParticipationController
 *
 * @category Controller
 * @package  Demofony2\AppBundle\Controller\Front
 * @author   David Romaní <david@flux.cat>
 */
class ParticipationController extends Controller
{
    /**
     * @Route("/participation/", name="demofony2_front_participation")
     */
    public function participationAction()
    {
        return $this->render('Front/participation.html.twig', array(
                'openDiscussions' => $this->getDoctrine()->getRepository('Demofony2AppBundle:ProcessParticipation')->get10LastOpenDiscussions(),
                'closeDiscussions' => $this->getDoctrine()->getRepository('Demofony2AppBundle:ProcessParticipation')->get10LastCloseDiscussions(),
                'openProposals' => $this->getDoctrine()->getRepository('Demofony2AppBundle:Proposal')->get10LastOpenProposals(),
                'closeProposals' => $this->getDoctrine()->getRepository('Demofony2AppBundle:Proposal')->get10LastCloseProposals(),
            ));
    }

    /**
     * @Route("/participation/calendar/", name="demofony2_front_participation_calendar")
     */
    public function participationCalendarAction()
    {
        return $this->render('Front/participation/calendar.html.twig');
    }

    /**
     * @Route("/participation/discussions/", name="demofony2_front_participation_discussions")
     */
    public function participationDiscussionsAction()
    {
        return $this->render('Front/participation/discussions.html.twig', array(
                'openDiscussions' => $this->getDoctrine()->getRepository('Demofony2AppBundle:ProcessParticipation')->get10LastOpenDiscussions(),
                'closeDiscussions' => $this->getDoctrine()->getRepository('Demofony2AppBundle:ProcessParticipation')->get10LastCloseDiscussions(),
            ));
    }

    /**
     * @param ProcessParticipation $discussionInstance
     *
     * @Route("/participation/discussions/{id}/{discussion}/", name="demofony2_front_participation_discussions_edit")
     * @ParamConverter("discussionInstance", class="Demofony2AppBundle:ProcessParticipation", options={"repository_method" = "getWithJoins"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function participationDiscussionsEditAction(ProcessParticipation $discussionInstance)
    {
        $discussionResponse = $this->forward('Demofony2AppBundle:Api/ProcessParticipation:getProcessparticipation', array('id' => $discussionInstance->getId()), array('_format' => 'json'));
        $commentsResponse = $this->forward('Demofony2AppBundle:Api/ProcessParticipationComment:cgetProcessparticipationComments', array('id' => $discussionInstance->getId()), array('_format' => 'json'));

        return $this->render('Front/participation/discussions.edit.html.twig', array(
                'discussion'      => $discussionInstance,
                'asyncDiscussion' => $discussionResponse->getContent(),
                'asyncComments'   => $commentsResponse->getContent(),
            ));
    }

    /**
     * @Route("/participation/porposals/", name="demofony2_front_participation_proposals")
     */
    public function participationProposalsAction()
    {
        return $this->render('Front/participation/proposals.html.twig', array(
                'openProposals' => $this->getDoctrine()->getRepository('Demofony2AppBundle:Proposal')->get10LastOpenProposals(),
                'closeProposals' => $this->getDoctrine()->getRepository('Demofony2AppBundle:Proposal')->get10LastCloseProposals(),
            ));
    }

    /**
     * @param  Request                                    $request
     * @Route("/participation/porposals/add-new-proposal/", name="demofony2_front_participation_proposals_new")
     * @Security("has_role('ROLE_USER')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function participationProposalsNewAction(Request $request)
    {
        $form = $this->createForm(new ProposalFormType(), new Proposal());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.proposal')->persist($form->getData());

            return new RedirectResponse($this->generateUrl('demofony2_front_participation_proposals_edit', array('id' => $form->getData()->getId())));
        }

        return $this->render('Front/participation/proposals.new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param  Request                                    $request
     * @param  Proposal                                   $proposal
     * @Route("/participation/porposals/edit/{id}/", name="demofony2_front_participation_proposals_edit")
     * @Security("has_role('ROLE_USER') && proposal.isAuthor(user)")
     * @ParamConverter("proposal", class="Demofony2AppBundle:Proposal")     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function participationProposalsEditAction(Request $request, Proposal $proposal)
    {
        $form = $this->createForm(new ProposalFormType(), $proposal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //to force Listener
            $proposal->setUpdatedAt(new \DateTime('now'));

//            foreach($proposal->getProposalAnswers() as  $pa){
////            ldd('entra');
//                $pa->setProposal($proposal);
////                ld($pa->getProposal());
//            }

//            $this->get('app.proposal')->flush();
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('info', $this->get('translator')->trans('proposal_edited'));

            return $this->redirectToRoute('demofony2_front_participation_proposals_edit', array('id' => $proposal->getId()));
        }

        return $this->render('Front/participation/proposals.new.html.twig', array('form' => $form->createView(), 'proposal' => $proposal));
    }
}
