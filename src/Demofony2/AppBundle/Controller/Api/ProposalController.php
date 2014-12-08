<?php
namespace Demofony2\AppBundle\Controller\Api;

use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\AppBundle\Entity\ProposalAnswer;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Demofony2\AppBundle\Entity\Comment;
use Demofony2\AppBundle\Entity\Vote;

/**
 * ProposalController
 * @Rest\NamePrefix("api_")
 * @package Demofony2\AppBundle\Controller\Api
 */
class ProposalController extends FOSRestController
{
    /**
     * Returns comments of level 0 and total count
     *
     * @param ParamFetcher $paramFetcher
     * @param Proposal     $proposal
     * @ApiDoc(
     *                                   section="Proposal",
     *                                   resource=true,
     *                                   description="Get Comments of level 0 and total count",
     *                                   statusCodes={
     *                                   200="Returned when successful",
     *                                   404={
     *                                   "Returned when proposal not found",
     *                                   }
     *                                   },
     *                                   requirements={
     *                                   {
     *                                   "name"="id",
     *                                   "dataType"="integer",
     *                                   "requirement"="\d+",
     *                                   "description"="Proposal id"
     *                                   }
     *                                   }
     *                                   )
     * @Rest\QueryParam(name="page", requirements="\d+", description="Page offset.", default=1, strict = false)
     * @Rest\QueryParam(name="limit", requirements="\d+", description="Page limit.", default=10, strict = false)
     * @ParamConverter("proposal", class="Demofony2AppBundle:Proposal")
     * @Rest\Get("/proposals/{id}/comments")
     * @Rest\View(serializerGroups={"list"})
     *
     * @return \Doctrine\Common\Collections\Collections
     */
    public function cgetProposalCommentsAction(
        ParamFetcher $paramFetcher,
        Proposal $proposal
    ) {
        $page = $paramFetcher->get('page');
        $limit = $paramFetcher->get('limit');
        list($comments, $count) = $this->getProposalManager()->getComments($proposal, $page, $limit);

        return ['comments' => $comments, 'count' => (int) $count];
    }

    /**
     * Returns children comments of level >0 and total count
     * @param ParamFetcher $paramFetcher
     * @param Proposal     $proposal
     * @param Comment      $comment
     * @ApiDoc(
     *                                   section="Proposal",
     *                                   resource=true,
     *                                   description="Get Children Comments of level > 0 and total count",
     *                                   statusCodes={
     *                                   200="Returned when successful",
     *                                   404={
     *                                   "Returned when proposal not found",
     *                                   "Returned when comment not found",
     *                                   },
     *                                   },
     *                                   requirements={
     *                                   {
     *                                   "name"="id",
     *                                   "dataType"="integer",
     *                                   "requirement"="\d+",
     *                                   "description"="Proposal id"
     *                                   },
     *                                   {
     *                                   "name"="comment_id",
     *                                   "dataType"="integer",
     *                                   "requirement"="\d+",
     *                                   "description"="Comment id"
     *                                   }
     *                                   }
     *                                   )
     * @Rest\QueryParam(name="page", requirements="\d+", description="Page offset.", default=1, strict = false)
     * @Rest\QueryParam(name="limit", requirements="\d+", description="Page limit.", default=10, strict = false)
     * @ParamConverter("proposal", class="Demofony2AppBundle:Proposal")
     * @ParamConverter("comment", class="Demofony2AppBundle:Comment", options={"id" = "comment_id"})
     * @Rest\Get("/proposals/{id}/comments/{comment_id}/childrens")
     * @Rest\View(serializerGroups={"children-list"})
     *
     * @return \Doctrine\Common\Collections\Collections
     */
    public function cgetProposalsCommentsChildrensAction(
        ParamFetcher $paramFetcher,
        Comment $comment,
        Proposal $proposal
    ) {
        $page = $paramFetcher->get('page');
        $limit = $paramFetcher->get('limit');
        list($comments, $count) = $this->getProposalManager()->getChildrenInComment(
            $proposal,
            $comment,
            $page,
            $limit
        );

        return ['comments' => $comments, 'count' => (int) $count];
    }

    /**
     * Create new comment
     * @param Request  $request
     * @param Proposal $proposal
     * @ApiDoc(
     *                           section="Proposal",
     *                           resource=true,
     *                           description="Post new comment",
     *                           statusCodes={
     *                           201="Returned when successful",
     *                           400={
     *                           "Returned when proposal not found",
     *                           },
     *                           401={
     *                           "Returned when user is not logged"
     *                           },
     *                           500={
     *                           "Returned when debate is not open",
     *                           "Parent is not consistent"
     *                           }
     *                           },
     *                           requirements={
     *                           {
     *                           "name"="id",
     *                           "dataType"="integer",
     *                           "requirement"="\d+",
     *                           "description"="Process participation id"
     *                           }
     *                           },
     *                           input="Demofony2\AppBundle\Form\Type\Api\CommentType",
     *                           )
     * @Rest\Post("/proposals/{id}/comments")
     * @ParamConverter("proposal", class="Demofony2AppBundle:Proposal")
     * @Rest\View(serializerGroups={"list"}, statusCode=201)
     * @Security("has_role('ROLE_USER')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postProposalsCommentsAction(Request $request, Proposal $proposal)
    {
        $result = $this->getProposalManager()->postComment($proposal, $request);

        return $result;
    }

    /**
     * Edit  comment
     * @param Request  $request
     * @param Proposal $proposal
     * @param Comment  $comment
     * @ApiDoc(
     *                           section="Proposal",
     *                           resource=true,
     *                           description="Edit comment",
     *                           statusCodes={
     *                           204="Returned when successful",
     *                           400={
     *                           "Returned when proposal not found",
     *                           "Returned when comment not found",
     *                           "Returned when comment not belongs to proposal",
     *                           },
     *                           401={
     *                           "Returned when user is not logged"
     *                           },
     *                           500={
     *                           "Returned when debate is not open",
     *                           "Parent is not consistent"
     *                           }
     *                           },
     *                           parameters={
     *                           {"name"="comment[title]", "dataType"="string", "required"=false, "description"="comment title"},
     *                           {"name"="comment[comment]", "dataType"="string", "required"=false, "description"="comment description"}
     *                           },
     *                           requirements={
     *                           {
     *                           "name"="id",
     *                           "dataType"="integer",
     *                           "requirement"="\d+",
     *                           "description"="Proposal id"
     *                           },
     *                           {
     *                           "name"="comment_id",
     *                           "dataType"="integer",
     *                           "requirement"="\d+",
     *                           "description"="Comment id"
     *                           }
     *                           }
     *                           )
     * @Rest\Put("/proposals/{id}/comments/{comment_id}")
     * @ParamConverter("proposal", class="Demofony2AppBundle:Proposal")
     * @ParamConverter("comment", class="Demofony2AppBundle:Comment", options={"id" = "comment_id"})
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_USER') && user === comment.getAuthor()")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putProposalsCommentsAction(
        Request $request,
        Proposal $proposal,
        Comment $comment
    ) {
        $result = $this->getProposalManager()->putComment($proposal, $comment, $request);

        return $result;
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
     * @Security("has_role('ROLE_USER') ")
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
     * @Security("has_role('ROLE_USER') ")
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
     * @Security("has_role('ROLE_USER') ")
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
