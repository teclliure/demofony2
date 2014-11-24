<?php
namespace Demofony2\AppBundle\Controller\Api;

use Demofony2\AppBundle\Entity\ProcessParticipation;
use Demofony2\AppBundle\Form\Type\CommentType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Util;

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
     * @Rest\View(serializerGroups={"list"})
     * @return \Doctrine\Common\Collections\Collections
     */
    public function cgetProcessparticipationCommentsAction(ParamFetcher $paramFetcher, ProcessParticipation $processParticipation)
    {
        $page = $paramFetcher->get('page');
        $limit = $paramFetcher->get('limit');
        $comments = $this->get('app.process_participation')->getComments($processParticipation->getId(), $page, $limit);

        return $comments;
    }

    /**
     * @ApiDoc(
     *     statusCodes={
     *         201="Returned when successful",
     *         400={
     *           "Returned when process participation not found",
     *         }
     *     },
     *  input="Demofony2\AppBundle\Form\Type\CommentType",
     * )
     */
    public function postCommentAction(Request $request)
    {
        $form = $this->createForm(new CommentType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($form->getData());
            $em->flush();
        }


        return $this->view(
            array(
                'id' => $form->getData()->getId(),

            ),
            201
        );


    }



}