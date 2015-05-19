<?php

namespace Demofony2\AppBundle\Controller\Front\Participation;

use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\AppBundle\Form\Type\Front\ProposalFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProposalController.
 *
 * @category Controller
 *
 * @author   David Romaní <david@flux.cat>
 */
class ProposalController extends Controller
{
    const ITEMS_BY_PAGE = 10;

    /**
     * @param int $open
     * @param int $closed
     * @Route("/participation/proposals/open{open}/", name="demofony2_front_participation_proposals_list_open")
     * @Route("/participation/proposals/closed{closed}/", name="demofony2_front_participation_proposals_list_closed")
     *
     * @return Response
     */
    public function participationProposalsListAction($open = 1, $closed = 1)
    {
        $openQueryBuilder = $this->getDoctrine()->getRepository(
            'Demofony2AppBundle:Proposal'
        )->getOpenProposalsQueryBuilder();
        $closedQueryBuilder = $this->getDoctrine()->getRepository(
            'Demofony2AppBundle:Proposal'
        )->getClosedProposalsQueryBuilder();

        $pagination = $this->get('app.pagination');
        $pagination->setItemsByPage(self::ITEMS_BY_PAGE)
            ->setFirstPaginationPage($open)
            ->setSecondPaginationPage($closed)
            ->setSecondPaginationQueryBuilder($closedQueryBuilder)
            ->setFirstPaginationQueryBuilder($openQueryBuilder)
            ->setFirstPaginationRoute('demofony2_front_participation_proposals_list_open')
            ->setSecondPaginationRoute('demofony2_front_participation_proposals_list_closed');
        list($open, $closed, $isOpenTab) = $pagination->getDoublePagination();

        return $this->render(
            'Front/participation/proposals.html.twig',
            array(
                'openProposals' => $open,
                'closeProposals' => $closed,
                'open'              => $isOpenTab,
            )
        );
    }

    /**
     * @param Request $request
     * @Route("/participation/porposals/add-new-proposal/", name="demofony2_front_participation_proposals_new")
     * @Security("has_role('ROLE_USER')")
     *
     * @return Response
     */
    public function participationProposalsNewAction(Request $request)
    {
        $form = $this->createForm(new ProposalFormType(), new Proposal());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Proposal $proposal */
            $proposal = $form->getData();
            if ($form->get('send')->isClicked()) {
                // pending
                $this->addFlash('info', $this->get('translator')->trans('proposal_created'));
                $proposal->setUserDraft(false);
            } else {
                // draft
                $this->addFlash('info', $this->get('translator')->trans('proposal_draft'));
                $proposal->setUserDraft(true);
            }
            $this->updateProposal($form->getData());
            $this->get('app.proposal')->persist($proposal);

            return new RedirectResponse(
                $this->generateUrl('fos_user_profile_public_show', array('username' => $this->getUser()->getUsername()))
            );
        }

        return $this->render('Front/participation/proposals.new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param Request  $request
     * @param Proposal $proposal
     * @Route("/participation/porposals/edit/{id}/{titleSlug}/", name="demofony2_front_participation_proposals_edit")
     * @Security("has_role('ROLE_USER') && proposal.isAuthor(user) && proposal.getUserDraft()")
     * @ParamConverter("proposal", class="Demofony2AppBundle:Proposal")     *
     *
     * @return Response
     */
    public function participationProposalsEditAction(Request $request, Proposal $proposal)
    {
        $form = $this->createForm(new ProposalFormType(), $proposal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('send')->isClicked()) {
                // pending
                $this->addFlash('info', $this->get('translator')->trans('proposal_created'));
                $proposal->setUserDraft(false);
            } else {
                // draft
                $this->addFlash('info', $this->get('translator')->trans('proposal_draft'));
                $proposal->setUserDraft(true);
            }
            $this->updateProposal($proposal);
            $this->get('app.proposal')->flush();
            $this->addFlash('info', $this->get('translator')->trans('proposal_edited'));

            return new RedirectResponse(
                $this->generateUrl('fos_user_profile_public_show', array('username' => $this->getUser()->getUsername()))
            );
        }

        return $this->render(
            'Front/participation/proposals.edit.html.twig',
            array('form' => $form->createView(), 'proposal' => $proposal)
        );
    }

    /**
     * @param Request  $request
     * @param Proposal $proposal
     * @Route("/participation/porposals/{id}/{titleSlug}/", name="demofony2_front_participation_proposals_show")
     * @ParamConverter("proposal", class="Demofony2AppBundle:Proposal")
     * @Security("is_granted('read', proposal)")
     *
     * @return Response
     */
    public function participationProposalsShowAction(Request $request, Proposal $proposal)
    {
        $discussionResponse = $this->forward(
            'Demofony2AppBundle:Api/Proposal:getProposal',
            array('id' => $proposal->getId()),
            array('_format' => 'json')
        );
        $commentsResponse = $this->forward(
            'Demofony2AppBundle:Api/ProposalComment:cgetProposalComments',
            array('id' => $proposal->getId()),
            array('_format' => 'json')
        );

        return $this->render(
            'Front/participation/proposals.show.html.twig',
            array(
                'proposal' => $proposal,
                'asyncDiscussion' => $discussionResponse->getContent(),
                'asyncComments' => $commentsResponse->getContent(),
            )
        );
    }

    /**
     * @param Proposal $object
     */
    private function updateProposal(Proposal $object)
    {
        foreach ($object->getProposalAnswers() as $pa) {
            $pa->setProposal($object);
        }
        foreach ($object->getDocuments() as $document) {
            $document->setProposal($object);
        }
    }
}
