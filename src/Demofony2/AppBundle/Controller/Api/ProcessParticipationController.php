<?php

namespace Demofony2\AppBundle\Controller\Api;

use Demofony2\AppBundle\Entity\ProcessParticipation;
use Demofony2\AppBundle\Entity\ProposalAnswer;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * ProcessParticipationController.
 *
 * @Rest\NamePrefix("api_")
 */
class ProcessParticipationController extends FOSRestController
{
    /**
     * Returns process participation.
     *
     * @param ProcessParticipation $processParticipation
     * @ApiDoc(
     *                                                   section="Process Participation",
     *                                                   resource=true,
     *                                                   description="Get process participation",
     *                                                   statusCodes={
     *                                                   200="Returned when successful",
     *                                                   404={
     *                                                   "Returned when process participation not found",
     *                                                   }
     *                                                   },
     *                                                   requirements={
     *                                                   {
     *                                                   "name"="id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Process participation id"
     *                                                   }
     *                                                   }
     *                                                   )
     * @ParamConverter("processParticipation", class="Demofony2AppBundle:ProcessParticipation")
     * @Rest\Get("/processparticipations/{id}")
     * @Rest\View(serializerGroups={"detail"})
     *
     * @return ProcessParticipation
     */
    public function getProcessparticipationAction(ProcessParticipation $processParticipation)
    {
        return $processParticipation;
    }

    /**
     * Vote process participation answer.
     *
     * @param Request              $request
     * @param ProcessParticipation $processParticipation
     * @param ProposalAnswer       $proposalAnswer
     * @ApiDoc(
     *                                                   section="Process Participation",
     *                                                   resource=true,
     *                                                   description="Vote a process participation",
     *                                                   statusCodes={
     *                                                   201="Returned when successful",
     *                                                   400={
     *                                                   "Returned when process participation not found",
     *                                                   "Returned when answer not found",
     *                                                   "Returned when answer not belongs to process participation",
     *                                                   },
     *                                                   401={
     *                                                   "Returned when user is not logged"
     *                                                   },
     *                                                   500={
     *                                                   "Returned when debate is not open",
     *                                                   }
     *                                                   },
     *                                                   input="Demofony2\AppBundle\Form\Type\Api\VoteType",
     *
     * )
     * @Rest\Post("/processparticipations/{id}/answers/{answer_id}/vote")
     * @ParamConverter("processParticipation", class="Demofony2AppBundle:ProcessParticipation")
     * @ParamConverter("proposalAnswer", class="Demofony2AppBundle:ProposalAnswer", options={"id" = "answer_id"})
     * @Rest\View(serializerGroups={"detail"}, statusCode=201)
     * @Security("has_role('ROLE_USER')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postProcessparticipationAnswersVoteAction(
        Request $request,
        ProcessParticipation $processParticipation,
        ProposalAnswer $proposalAnswer
    ) {
        $result = $this->getProcessParticipationManager()->postVote(
            $processParticipation,
            $proposalAnswer,
            $this->getUser(),
            $request
        );

        return ['vote' => $result, 'votes_count' => $proposalAnswer->getVotesCount()];
    }

    /**
     * Edit a Vote.
     *
     * @param Request              $request
     * @param ProcessParticipation $processParticipation
     * @param ProposalAnswer       $proposalAnswer
     * @ApiDoc(
     *                                                   section="Process Participation",
     *                                                   resource=true,
     *                                                   description="Edit a vote",
     *                                                   statusCodes={
     *                                                   204="Returned when successful",
     *                                                   400={
     *                                                   "Returned when process participation not found",
     *                                                   "Returned when vote not found",
     *                                                   "Returned when vote not belongs to proposal answer",
     *                                                   "Returned when proposal answer not belongs to process participation",
     *                                                   },
     *                                                   401={
     *                                                   "Returned when user is not logged",
     *                                                   "Returned vote not belongs to user logged",
     *                                                   },
     *                                                   500={
     *                                                   "Returned when debate is not open",
     *                                                   }
     *                                                   },
     *                                                   input="Demofony2\AppBundle\Form\Type\Api\VoteType",
     *
     * )
     * @Rest\Put("/processparticipations/{id}/answers/{answer_id}/vote")
     * @ParamConverter("processParticipation", class="Demofony2AppBundle:ProcessParticipation")
     * @ParamConverter("proposalAnswer", class="Demofony2AppBundle:ProposalAnswer", options={"id" = "answer_id"})
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_USER')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putProcessparticipationAnswersVoteAction(
        Request $request,
        ProcessParticipation $processParticipation,
        ProposalAnswer $proposalAnswer
    ) {
        $result = $this->getProcessParticipationManager()->editVote(
            $processParticipation,
            $proposalAnswer,
            $this->getUser(),
            $request
        );

        return $result;
    }

    /**
     * delete a Vote.
     *
     * @param ProcessParticipation $processParticipation
     * @param ProposalAnswer       $proposalAnswer
     * @ApiDoc(
     *                                                   section="Process Participation",
     *                                                   resource=true,
     *                                                   description="Delete vote",
     *                                                   statusCodes={
     *                                                   204="Returned when successful",
     *                                                   400={
     *                                                   "Returned when process participation not found",
     *                                                   "Returned when proposal answer not belongs to process participation",
     *                                                   "Returned when proposal answer not found",
     *                                                   "Returned when vote not found",
     *                                                   "Returned when vote not belongs to proposal answer",
     *                                                   },
     *                                                   401={
     *                                                   "Returned when user is not logged",
     *                                                   "Returned vote not belongs to user logged",
     *                                                   },
     *                                                   500={
     *                                                   "Returned when debate is not open",
     *                                                   }
     *                                                   },
     *
     * )
     * @Rest\Delete("/processparticipations/{id}/answers/{answer_id}/vote")
     * @ParamConverter("processParticipation", class="Demofony2AppBundle:ProcessParticipation")
     * @ParamConverter("proposalAnswer", class="Demofony2AppBundle:ProposalAnswer", options={"id" = "answer_id"})
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_USER')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteProcessparticipationAnswersVoteAction(
        ProcessParticipation $processParticipation,
        ProposalAnswer $proposalAnswer
    ) {
        $result = $this->getProcessParticipationManager()->deleteVote(
            $processParticipation,
            $proposalAnswer,
            $this->getUser()
        );

        return $result;
    }

    protected function getProcessParticipationManager()
    {
        return $this->get('app.process_participation');
    }
}
