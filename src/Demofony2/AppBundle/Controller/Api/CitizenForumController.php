<?php

namespace Demofony2\AppBundle\Controller\Api;

use Demofony2\AppBundle\Entity\CitizenForum;
use Demofony2\AppBundle\Entity\ProposalAnswer;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * CitizenForumController.
 *
 * @Rest\NamePrefix("api_")
 */
class CitizenForumController extends FOSRestController
{
    /**
     * Returns citizen Forum.
     *
     * @param CitizenForum $citizenForum
     * @ApiDoc(
     *                                   section="Citizen Forum",
     *                                   resource=true,
     *                                   description="Get citizen forum",
     *                                   statusCodes={
     *                                   200="Returned when successful",
     *                                   404={
     *                                   "Returned when citizen forum not found",
     *                                   }
     *                                   },
     *                                   requirements={
     *                                   {
     *                                   "name"="id",
     *                                   "dataType"="integer",
     *                                   "requirement"="\d+",
     *                                   "description"="Citizen forum id"
     *                                   }
     *                                   }
     *                                   )
     * @ParamConverter("citizenForum", class="Demofony2AppBundle:CitizenForum")
     * @Rest\Get("/citizenforums/{id}")
     * @Rest\View(serializerGroups={"detail"})
     *
     * @return CitizenForum
     */
    public function getCitizenForumAction(CitizenForum $citizenForum)
    {
        return $citizenForum;
    }

    /**
     * Vote citizen forum answer.
     *
     * @param Request        $request
     * @param CitizenForum   $citizenForum
     * @param ProposalAnswer $proposalAnswer
     * @ApiDoc(
     *                                       section="Citizen Forum",
     *                                       resource=true,
     *                                       description="Vote a citizen forum",
     *                                       statusCodes={
     *                                       201="Returned when successful",
     *                                       400={
     *                                       "Returned when citizen forum not found",
     *                                       "Returned when answer not found",
     *                                       "Returned when answer not belongs to citizen forum",
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
     * @Rest\Post("/citizenforums/{id}/answers/{answer_id}/vote")
     * @ParamConverter("citizenForum", class="Demofony2AppBundle:CitizenForum")
     * @ParamConverter("proposalAnswer", class="Demofony2AppBundle:ProposalAnswer", options={"id" = "answer_id"})
     * @Rest\View(serializerGroups={"detail"}, statusCode=201)
     * @Security("has_role('ROLE_USER')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postCitizenForumAnswersVoteAction(
        Request $request,
        CitizenForum $citizenForum,
        ProposalAnswer $proposalAnswer
    ) {
        $result = $this->getCitizenForumManager()->postVote(
            $citizenForum,
            $proposalAnswer,
            $this->getUser(),
            $request
        );

        return ['vote' => $result, 'votes_count' => $proposalAnswer->getVotesCount()];
    }

    /**
     * Edit a Vote.
     *
     * @param Request        $request
     * @param CitizenForum   $citizenForum
     * @param ProposalAnswer $proposalAnswer
     * @ApiDoc(
     *                                       section="Citizen Forum",
     *                                       resource=true,
     *                                       description="Edit a vote",
     *                                       statusCodes={
     *                                       204="Returned when successful",
     *                                       400={
     *                                       "Returned when citizen forum not found",
     *                                       "Returned when vote not found",
     *                                       "Returned when vote not belongs to proposal answer",
     *                                       "Returned when proposal answer not belongs to citizen forum",
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
     * @Rest\Put("/citizenforums/{id}/answers/{answer_id}/vote")
     * @ParamConverter("citizenForum", class="Demofony2AppBundle:CitizenForum")
     * @ParamConverter("proposalAnswer", class="Demofony2AppBundle:ProposalAnswer", options={"id" = "answer_id"})
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_USER')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putCitizenForumAnswersVoteAction(
        Request $request,
        CitizenForum $citizenForum,
        ProposalAnswer $proposalAnswer
    ) {
        $result = $this->getCitizenForumManager()->editVote(
            $citizenForum,
            $proposalAnswer,
            $this->getUser(),
            $request
        );

        return $result;
    }

    /**
     * delete a Vote.
     *
     * @param CitizenForum   $citizenForum
     * @param ProposalAnswer $proposalAnswer
     * @ApiDoc(
     *                                       section="Citizen Forum",
     *                                       resource=true,
     *                                       description="Delete vote",
     *                                       statusCodes={
     *                                       204="Returned when successful",
     *                                       400={
     *                                       "Returned when citizen forum not found",
     *                                       "Returned when proposal answer not belongs to citizen forum",
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
     * @Rest\Delete("/citizenforums/{id}/answers/{answer_id}/vote")
     * @ParamConverter("citizenForum", class="Demofony2AppBundle:CitizenForum")
     * @ParamConverter("proposalAnswer", class="Demofony2AppBundle:ProposalAnswer", options={"id" = "answer_id"})
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_USER')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteCitizenForumAnswersVoteAction(
        CitizenForum $citizenForum,
        ProposalAnswer $proposalAnswer
    ) {
        $result = $this->getCitizenForumManager()->deleteVote(
            $citizenForum,
            $proposalAnswer,
            $this->getUser()
        );

        return $result;
    }

    protected function getCitizenForumManager()
    {
        return $this->get('app.citizen_forum');
    }
}
