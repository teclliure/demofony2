<?php
namespace Demofony2\AppBundle\Controller\Api;

use Demofony2\AppBundle\Entity\ProcessParticipation;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Util;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Demofony2\AppBundle\Entity\Comment;


class ProcessParticipationController extends FOSRestController
{
    /**
     * @param ParamFetcher         $paramFetcher
     * @param ProcessParticipation $processParticipation
     * @ApiDoc(
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when process participation not found",
     *         }
     *     }
     * )
     * @Rest\QueryParam(name="page", requirements=".+", description="Page offset.", default=1)
     * @Rest\QueryParam(name="limit", requirements=".+", description="Page limit.", default=10)
     * @ParamConverter("processParticipation", class="Demofony2AppBundle:ProcessParticipation")
     * @Rest\Get("/processparticipations/{id}/comments")
     * @Rest\View(serializerGroups={"list"})
     *
     * @return \Doctrine\Common\Collections\Collections
     */
    public function cgetProcessparticipationCommentsAction(
        ParamFetcher $paramFetcher,
        ProcessParticipation $processParticipation
    ) {
        $page = $paramFetcher->get('page');
        $limit = $paramFetcher->get('limit');
        list($comments, $count) = $this->get('app.process_participation')->getComments($processParticipation->getId(), $page, $limit);

        return ['comments' => $comments, 'count' => (int) $count];
    }

    /**
     * @param ParamFetcher         $paramFetcher
     * @param ProcessParticipation $processParticipation
     * @param Comment              $comment
     * @ApiDoc(
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when process participation not found",
     *           "Returned when comment not found",
     *         }
     *     }
     * )
     * @Rest\QueryParam(name="page", requirements=".+", description="Page offset.", default=1)
     * @Rest\QueryParam(name="limit", requirements=".+", description="Page limit.", default=10)
     * @ParamConverter("processParticipation", class="Demofony2AppBundle:ProcessParticipation")
     * @ParamConverter("comment", class="Demofony2AppBundle:Comment", options={"id" = "comment_id"})
     * @Rest\Get("/processparticipations/{id}/comments/{comment_id}/children")
     * @Rest\View(serializerGroups={"children-list"})
     *
     * @return \Doctrine\Common\Collections\Collections
     */
    public function cgetProcessparticipationCommentsChildrenAction(
        ParamFetcher $paramFetcher,
        Comment $comment,
        ProcessParticipation $processParticipation
    ) {
        $page = $paramFetcher->get('page');
        $limit = $paramFetcher->get('limit');
        list($comments, $count) = $this->get('app.process_participation')->getChildrenInComment(
            $processParticipation,
            $comment,
            $page,
            $limit
        );

        return ['comments' => $comments, 'count' => (int) $count];
    }

    /**
     * @param Request              $request
     * @param ProcessParticipation $processParticipation
     * @ApiDoc(
     *     statusCodes={
     *         201="Returned when successful",
     *         400={
     *           "Returned when process participation not found",
     *         }
     *     },
     *   input="Demofony2\AppBundle\Form\Type\Api\CommentType",
     * )
     * @Rest\Post("/processparticipations/{id}/comment")
     * @ParamConverter("processParticipation", class="Demofony2AppBundle:ProcessParticipation")
     * @Rest\View(serializerGroups={"list"}, statusCode=201)
     * @Security("has_role('ROLE_USER')")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postProcessparticipationCommentAction(Request $request, ProcessParticipation $processParticipation)
    {
        $result = $this->getProcessParticipationManager()->postComment($processParticipation, $request);

        return $result;
    }

    /**
     * @param Request              $request
     * @param ProcessParticipation $processParticipation
     * @param Comment              $comment
     * @ApiDoc(
     *     statusCodes={
     *         204="Returned when successful",
     *         400={
     *           "Returned when process participation not found",
     *           "Returned when comment not found",
     *           "Returned when comment not belongs to process participation",
     *         }
     *     },
     *     parameters={
     *      {"name"="comment[title]", "dataType"="string", "required"=false, "description"="comment title"},
     *      {"name"="comment[comment]", "dataType"="string", "required"=false, "description"="comment description"}
     *      }
     * )
     * @Rest\Put("/processparticipations/{id}/comment/{comment_id}")
     * @ParamConverter("processParticipation", class="Demofony2AppBundle:ProcessParticipation")
     * @ParamConverter("comment", class="Demofony2AppBundle:Comment", options={"id" = "comment_id"})
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_USER') && user === comment.getAuthor()")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putProcessparticipationCommentAction(
        Request $request,
        ProcessParticipation $processParticipation,
        Comment $comment
    ) {
        $result = $this->getProcessParticipationManager()->putComment($processParticipation, $comment, $request);

        return $result;
    }

    protected function getProcessParticipationManager()
    {
        return $this->get('app.process_participation');
    }


}