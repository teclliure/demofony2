<?php
namespace Demofony2\AppBundle\Controller\Api;

use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\AppBundle\Entity\ProposalAnswer;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * ProposalController
 * @Rest\NamePrefix("api_")
 * @package Demofony2\AppBundle\Controller\Api
 */
class ProposalController extends FOSRestController
{
    /**
     * Returns proposal
     *
     * @param Proposal $proposal
     * @ApiDoc(
     *                           section="Proposal",
     *                           resource=true,
     *                           description="Get proposaln",
     *                           statusCodes={
     *                           200="Returned when successful",
     *                           404={
     *                           "Returned when proposal not found",
     *                           }
     *                           },
     *                           requirements={
     *                           {
     *                           "name"="id",
     *                           "dataType"="integer",
     *                           "requirement"="\d+",
     *                           "description"="Proposal id"
     *                           }
     *                           }
     *                           )
     * @ParamConverter("proposal", class="Demofony2AppBundle:Proposal")
     * @Rest\Get("/proposals/{id}")
     * @Rest\View(serializerGroups={"detail"})
     * @Security("is_granted('read', proposal)")
     *
     * @return Proposal
     */
    public function getProposalAction(Proposal $proposal)
    {
        return $proposal;
    }

    /**
     * Vote  proposal answer
     * @param Request        $request
     * @param Proposal       $proposal
     * @param ProposalAnswer $proposalAnswer
     * @ApiDoc(
     *                                       section="Proposal",
     *                                       resource=true,
     *                                       description="Edit comment",
     *                                       statusCodes={
     *                                       201="Returned when successful",
     *                                       400={
     *                                       "Returned when proposal not found",
     *                                       "Returned when answer not found",
     *                                       "Returned when answer not belongs to proposal",
     *                                       },
     *                                       401={
     *                                       "Returned when user is not logged"
     *                                       },
     *                                       500={
     *                                       "Returned when debate is not open",
     *                                       }
     *                                       },
     *                                       input="Demofony2\AppBundle\Form\Type\Api\VoteType",
     *
     * )
     * @Rest\Post("/proposals/{id}/answers/{answer_id}/vote")
     * @ParamConverter("proposal", class="Demofony2AppBundle:Proposal")
     * @ParamConverter("proposalAnswer", class="Demofony2AppBundle:ProposalAnswer", options={"id" = "answer_id"})
     * @Rest\View(serializerGroups={"detail"}, statusCode=201)
     * @Security("is_granted('read', proposal) && has_role('ROLE_USER') ")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postProposalAnswersVoteAction(Request $request, Proposal $proposal, ProposalAnswer $proposalAnswer)
    {
        $result = $this->getProposalManager()->postVote($proposal, $proposalAnswer, $this->getUser(), $request);

        return ['vote' => $result, 'votes_count' => $proposalAnswer->getVotesCount()];
    }

    /**
     * Edit a Vote
     * @param Request        $request
     * @param Proposal       $proposal
     * @param ProposalAnswer $proposalAnswer
     * @ApiDoc(
     *                                       section="Proposal",
     *                                       resource=true,
     *                                       description="Edit a vote",
     *                                       statusCodes={
     *                                       204="Returned when successful",
     *                                       400={
     *                                       "Returned when proposal not found",
     *                                       "Returned when vote not found",
     *                                       "Returned when vote not belongs to proposal answer",
     *                                       "Returned when proposal answer not belongs to proposal",
     *                                       },
     *                                       401={
     *                                       "Returned when user is not logged",
     *                                       "Returned vote not belongs to user logged",
     *                                       },
     *                                       500={
     *                                       "Returned when debate is not open",
     *                                       }
     *                                       },
     *                                       input="Demofony2\AppBundle\Form\Type\Api\VoteType",
     *
     * )
     * @Rest\Put("/proposals/{id}/answers/{answer_id}/vote")
     * @ParamConverter("proposal", class="Demofony2AppBundle:Proposal")
     * @ParamConverter("proposalAnswer", class="Demofony2AppBundle:ProposalAnswer", options={"id" = "answer_id"})
     * @Rest\View(statusCode=204)
     * @Security("is_granted('read', proposal) && has_role('ROLE_USER') ")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putProposalAnswersVoteAction(Request $request, Proposal $proposal, ProposalAnswer $proposalAnswer)
    {
        $result = $this->getProposalManager()->editVote($proposal, $proposalAnswer, $this->getUser(), $request);

        return $result;
    }

    /**
     * delete a Vote
     * @param Proposal       $proposal
     * @param ProposalAnswer $proposalAnswer
     * @ApiDoc(
     *                                       section="Proposal",
     *                                       resource=true,
     *                                       description="Delete vote",
     *                                       statusCodes={
     *                                       204="Returned when successful",
     *                                       400={
     *                                       "Returned when proposal not found",
     *                                       "Returned when proposal answer not belongs to process participation",
     *                                       "Returned when proposal answer not found",
     *                                       "Returned when vote not found",
     *                                       "Returned when vote not belongs to proposal answer",
     *                                       },
     *                                       401={
     *                                       "Returned when user is not logged",
     *                                       "Returned vote not belongs to user logged",
     *                                       },
     *                                       500={
     *                                       "Returned when debate is not open",
     *                                       }
     *                                       },
     *
     * )
     * @Rest\Delete("/proposals/{id}/answers/{answer_id}/vote")
     * @ParamConverter("proposal", class="Demofony2AppBundle:Proposal")
     * @ParamConverter("proposalAnswer", class="Demofony2AppBundle:ProposalAnswer", options={"id" = "answer_id"})
     * @Rest\View(statusCode=204)
     * @Security("is_granted('read', proposal) && has_role('ROLE_USER') ")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteProposalAnswersVoteAction(Proposal $proposal, ProposalAnswer $proposalAnswer)
    {
        $result = $this->getProposalManager()->deleteVote($proposal, $proposalAnswer, $this->getUser());

        return $result;
    }

    protected function getProposalManager()
    {
        return $this->get('app.proposal');
    }
}
