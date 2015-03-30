<?php

namespace Demofony2\AppBundle\Controller\Api;

use Demofony2\AppBundle\Entity\CitizenForum;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Demofony2\AppBundle\Entity\Comment;

/**
 * CitizenForumCommentController
 * @Rest\NamePrefix("api_")
 */
class CitizenForumCommentController extends FOSRestController
{
    /**
     * Returns comments of level 0 and total count
     *
     * @param ParamFetcher         $paramFetcher
     * @param CitizenForum $citizenForum
     * @ApiDoc(
     *                                                   section="Citizen Forum",
     *                                                   resource=true,
     *                                                   description="Get Comments of level 0 and total count",
     *                                                   statusCodes={
     *                                                   200="Returned when successful",
     *                                                   404={
     *                                                   "Returned when citizen forum not found",
     *                                                   }
     *                                                   },
     *                                                   requirements={
     *                                                   {
     *                                                   "name"="id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Citizen Forum id"
     *                                                   }
     *                                                   }
     *                                                   )
     * @Rest\QueryParam(name="page", requirements="\d+", description="Page offset.", default=1, strict = false)
     * @Rest\QueryParam(name="limit", requirements="\d+", description="Page limit.", default=10, strict = false)
     * @ParamConverter("citizenForum", class="Demofony2AppBundle:CitizenForum")
     * @Rest\Get("/citizenforums/{id}/comments")
     * @Rest\View(serializerGroups={"list"})
     *
     * @return \Doctrine\Common\Collections\Collections
     */
    public function cgetCitizenForumCommentsAction(
        ParamFetcher $paramFetcher,
        CitizenForum $citizenForum
    ) {
        $page = $paramFetcher->get('page');
        $limit = $paramFetcher->get('limit');
        list($comments, $count) = $this->getCitizenForumManager()->getComments(
            $citizenForum,
            $page,
            $limit
        );

        return ['comments' => $comments, 'count' => (int) $count];
    }

    /**
     * Returns children comments of level >0 and total count
     *
     * @param ParamFetcher         $paramFetcher
     * @param CitizenForum         $citizenForum
     * @param Comment              $comment
     * @ApiDoc(
     *                                                   section="Citizen Forum",
     *                                                   resource=true,
     *                                                   description="Get Children Comments of level > 0 and total count",
     *                                                   statusCodes={
     *                                                   200="Returned when successful",
     *                                                   404={
     *                                                   "Returned when citizen forum not found",
     *                                                   "Returned when comment not found",
     *                                                   }
     *                                                   },
     *                                                   requirements={
     *                                                   {
     *                                                   "name"="id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Citizen Forum id"
     *                                                   },
     *                                                   {
     *                                                   "name"="comment_id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Comment id"
     *                                                   }
     *                                                   }
     *                                                   )
     * @Rest\QueryParam(name="page", requirements="\d+", description="Page offset.", default=1, strict = false)
     * @Rest\QueryParam(name="limit", requirements="\d+", description="Page limit.", default=10, strict = false)
     * @ParamConverter("citizenForum", class="Demofony2AppBundle:CitizenForum")
     * @ParamConverter("comment", class="Demofony2AppBundle:Comment", options={"id" = "comment_id"})
     * @Rest\Get("/citizenForums/{id}/comments/{comment_id}/childrens")
     * @Rest\View(serializerGroups={"children-list"})
     *
     * @return \Doctrine\Common\Collections\Collections
     */
    public function cgetCitizenForumCommentsChildrensAction(
        ParamFetcher $paramFetcher,
        Comment $comment,
        CitizenForum $citizenForum
    ) {
        $page = $paramFetcher->get('page');
        $limit = $paramFetcher->get('limit');
        list($comments, $count) = $this->getCitizenForumManager()->getChildrenInComment(
            $citizenForum,
            $comment,
            $page,
            $limit
        );

        return ['comments' => $comments, 'count' => (int) $count];
    }

    /**
     * Create new comment
     *
     * @param Request              $request
     * @param CitizenForum         $citizenForum
     * @ApiDoc(
     *                                                   section="CitizenForum",
     *                                                   resource=true,
     *                                                   description="Post new comment",
     *                                                   statusCodes={
     *                                                   201="Returned when successful",
     *                                                   400={
     *                                                   "Returned when citizen forum not found",
     *                                                   },
     *                                                   401={
     *                                                   "Returned when user is not logged"
     *                                                   },
     *                                                   500={
     *                                                   "Returned when debate is not open",
     *                                                   "Parent is not consistent"
     *                                                   }
     *                                                   },
     *                                                   requirements={
     *                                                   {
     *                                                   "name"="id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Citizen forum id"
     *                                                   }
     *                                                   },
     *                                                   input="Demofony2\AppBundle\Form\Type\Api\CommentType",
     *                                                   )
     * @Rest\Post("/citizenforums/{id}/comments")
     * @ParamConverter("citizenForum", class="Demofony2AppBundle:CitizenForum")
     * @Rest\View(serializerGroups={"list"}, statusCode=201)
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postCitizenForumCommentsAction(Request $request, CitizenForum $citizenForum)
    {
        $result = $this->getCitizenForumManager()->postComment($citizenForum, $request);

        return $result;
    }

    /**
     * Edit  comment
     *
     * @param Request              $request
     * @param CitizenForum         $citizenForum
     * @param Comment              $comment
     * @ApiDoc(
     *                                                   section="Citizen Forum",
     *                                                   resource=true,
     *                                                   description="Edit comment",
     *                                                   statusCodes={
     *                                                   204="Returned when successful",
     *                                                   400={
     *                                                   "Returned when citizen forum not found",
     *                                                   "Returned when comment not found",
     *                                                   "Returned when comment not belongs to citizen forum",
     *                                                   },
     *                                                   401={
     *                                                   "Returned when user is not logged"
     *                                                   },
     *                                                   500={
     *                                                   "Returned when debate is not open",
     *                                                   "Parent is not consistent"
     *                                                   }
     *                                                   },
     *                                                   parameters={
     *                                                   {"name"="comment[title]", "dataType"="string", "required"=false, "description"="comment title"},
     *                                                   {"name"="comment[comment]", "dataType"="string", "required"=false, "description"="comment description"}
     *                                                   },
     *                                                   requirements={
     *                                                   {
     *                                                   "name"="id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Citizen forum id"
     *                                                   },
     *                                                   {
     *                                                   "name"="comment_id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Comment id"
     *                                                   }
     *                                                   }
     *                                                   )
     * @Rest\Put("/citizenforums/{id}/comments/{comment_id}")
     * @ParamConverter("citizenForum", class="Demofony2AppBundle:CitizenForum")
     * @ParamConverter("comment", class="Demofony2AppBundle:Comment", options={"id" = "comment_id"})
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_USER') && user === comment.getAuthor()")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putCitizenForumCommentsAction(
        Request $request,
        CitizenForum $citizenForum,
        Comment $comment
    ) {
        $result = $this->getCitizenForumManager()->putComment($citizenForum, $comment, $request);

        return $result;
    }

    /**
     * Like  comment
     *
     * @param Request              $request
     * @param CitizenForum         $citizenForum
     * @param Comment              $comment
     * @ApiDoc(
     *                                                   section="Citizen Forum",
     *                                                   resource=true,
     *                                                   description="Like comment",
     *                                                   statusCodes={
     *                                                   201="Returned when successful",
     *                                                   400={
     *                                                   "Returned when citizen forum not found",
     *                                                   "Returned when comment not found",
     *                                                   "Returned when comment not belongs to citizen forum",
     *                                                   },
     *                                                   401={
     *                                                   "Returned when user is not logged"
     *                                                   },
     *                                                   500={
     *                                                   "Returned when debate is not open",
     *                                                   "Returned when user already voted"
     *                                                   }
     *                                                   },
     *                                                   requirements={
     *                                                   {
     *                                                   "name"="id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Citizen Forum id"
     *                                                   },
     *                                                   {
     *                                                   "name"="comment_id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Comment id"
     *                                                   }
     *                                                   }
     *                                                   )
     * @Rest\Post("/citizenforums/{id}/comments/{comment_id}/like")
     * @ParamConverter("citizenForum", class="Demofony2AppBundle:CitizenForum")
     * @ParamConverter("comment", class="Demofony2AppBundle:Comment", options={"id" = "comment_id"})
     * @Rest\View(serializerGroups={"list"}, statusCode=201)
     * @Security("has_role('ROLE_USER')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postCitizenForumCommentsLikeAction(
        Request $request,
        CitizenForum $citizenForum,
        Comment $comment
    ) {
        $result = $this->getCitizenForumManager()->likeComment($citizenForum, $comment);

        return $result;
    }

    /**
     * Unlike  comment
     *
     * @param Request              $request
     * @param CitizenForum         $citizenForum
     * @param Comment              $comment
     * @ApiDoc(
     *                                                   section="Citizen Forum",
     *                                                   resource=true,
     *                                                   description="Unlike comment",
     *                                                   statusCodes={
     *                                                   201="Returned when successful",
     *                                                   400={
     *                                                   "Returned when citizen forum not found",
     *                                                   "Returned when comment not found",
     *                                                   "Returned when comment not belongs to citizen forum",
     *                                                   },
     *                                                   401={
     *                                                   "Returned when user is not logged"
     *                                                   },
     *                                                   500={
     *                                                   "Returned when debate is not open",
     *                                                   "Returned when user already voted"
     *                                                   }
     *                                                   },
     *                                                   requirements={
     *                                                   {
     *                                                   "name"="id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Citizen forum id"
     *                                                   },
     *                                                   {
     *                                                   "name"="comment_id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Comment id"
     *                                                   }
     *                                                   }
     *                                                   )
     * @Rest\Post("/citizenforums/{id}/comments/{comment_id}/unlike")
     * @ParamConverter("processParticipation", class="Demofony2AppBundle:CitizenForum")
     * @ParamConverter("comment", class="Demofony2AppBundle:Comment", options={"id" = "comment_id"})
     * @Rest\View(serializerGroups={"list"}, statusCode=201)
     * @Security("has_role('ROLE_USER')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postCitizenForumCommentsUnlikeAction(
        Request $request,
        CitizenForum $citizenForum,
        Comment $comment
    ) {
        $result = $this->getCitizenForumManager()->unLikeComment($citizenForum, $comment);

        return $result;
    }

    /**
     * Delete Like  comment
     *
     * @param Request              $request
     * @param CitizenForum         $citizenForum
     * @param Comment              $comment
     * @ApiDoc(
     *                                                   section="Citizen Forum",
     *                                                   resource=true,
     *                                                   description="Delete Like comment",
     *                                                   statusCodes={
     *                                                   201="Returned when successful",
     *                                                   400={
     *                                                   "Returned when citizen forum not found",
     *                                                   "Returned when comment not found",
     *                                                   "Returned when comment not belongs to citizen forum",
     *                                                   },
     *                                                   401={
     *                                                   "Returned when user is not logged"
     *                                                   },
     *                                                   500={
     *                                                   "Returned when debate is not open",
     *                                                   "Returned when user already voted"
     *                                                   }
     *                                                   },
     *                                                   requirements={
     *                                                   {
     *                                                   "name"="id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Citizen forum id"
     *                                                   },
     *                                                   {
     *                                                   "name"="comment_id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Comment id"
     *                                                   }
     *                                                   }
     *                                                   )
     * @Rest\Delete("/citizenforums/{id}/comments/{comment_id}/like")
     * @ParamConverter("citizenForum", class="Demofony2AppBundle:CitizenForum")
     * @ParamConverter("comment", class="Demofony2AppBundle:Comment", options={"id" = "comment_id"})
     * @Rest\View(serializerGroups={"list"}, statusCode=201)
     * @Security("has_role('ROLE_USER')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteCitizenForumCommentsLikeAction(
        Request $request,
        CitizenForum $citizenForum,
        Comment $comment
    ) {
        $result = $this->getCitizenForumManager()->deleteLikeComment($citizenForum, $comment, $this->getUser());

        return $result;
    }

    /**
     * Delete Unlike  comment
     *
     * @param Request              $request
     * @param CitizenForum         $citizenForum
     * @param Comment              $comment
     * @ApiDoc(
     *                                                   section="Citizen Forum",
     *                                                   resource=true,
     *                                                   description="Delete Unlike comment",
     *                                                   statusCodes={
     *                                                   201="Returned when successful",
     *                                                   400={
     *                                                   "Returned when citizen forum not found",
     *                                                   "Returned when comment not found",
     *                                                   "Returned when comment not belongs to citizen forum",
     *                                                   },
     *                                                   401={
     *                                                   "Returned when user is not logged"
     *                                                   },
     *                                                   500={
     *                                                   "Returned when debate is not open",
     *                                                   "Returned when user already voted"
     *                                                   }
     *                                                   },
     *                                                   requirements={
     *                                                   {
     *                                                   "name"="id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Citizen forum id"
     *                                                   },
     *                                                   {
     *                                                   "name"="comment_id",
     *                                                   "dataType"="integer",
     *                                                   "requirement"="\d+",
     *                                                   "description"="Comment id"
     *                                                   }
     *                                                   }
     *                                                   )
     * @Rest\Delete("/citizenforums/{id}/comments/{comment_id}/unlike")
     * @ParamConverter("citizenForum", class="Demofony2AppBundle:CitizenForum")
     * @ParamConverter("comment", class="Demofony2AppBundle:Comment", options={"id" = "comment_id"})
     * @Rest\View(serializerGroups={"list"}, statusCode=201)
     * @Security("has_role('ROLE_USER')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteCitizenForumCommentsUnLikeAction(
        Request $request,
        CitizenForum $citizenForum,
        Comment $comment
    ) {
        $result = $this->getCitizenForumManager()->deleteUnlikeComment($citizenForum, $comment, $this->getUser());

        return $result;
    }

    protected function getCitizenForumManager()
    {
        return $this->get('app.citizen_forum');
    }
}
