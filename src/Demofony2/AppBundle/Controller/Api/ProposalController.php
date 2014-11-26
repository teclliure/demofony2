<?php
namespace Demofony2\AppBundle\Controller\Api;

use Demofony2\AppBundle\Entity\Proposal;
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


/**
 * ProposalController
 * @package Demofony2\AppBundle\Controller\Api
 */
class ProposalController extends FOSRestController
{
    /**
     * Returns comments of level 0 and total count
     *
     * @param ParamFetcher         $paramFetcher
     * @param Proposal $proposal
     * @ApiDoc(
     *     section="Proposal",
     *     resource=true,
     *     description="Get Comments of level 0 and total count",
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when proposal not found",
     *         }
     *     },
     *      requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Proposal id"
     *      }
     *     }
     * )
     * @Rest\QueryParam(name="page", requirements="\d+", description="Page offset.", default=1, strict = false)
     * @Rest\QueryParam(name="limit", requirements="\d+", description="Page limit.", default=10, strict = false)
     * @ParamConverter("proposal", class="Demofony2AppBundle:Proposal")
     * @Rest\Get("/proposal/{id}/comments")
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

    protected function getProposalManager()
    {
        return $this->get('app.proposal');
    }
}