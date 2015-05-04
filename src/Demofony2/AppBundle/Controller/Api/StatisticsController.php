<?php

namespace Demofony2\AppBundle\Controller\Api;

use Demofony2\AppBundle\Entity\Proposal;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * StatisticsController.
 *
 * @Rest\NamePrefix("api_")
 * @Security("has_role('ROLE_ADMIN')")
 */
class StatisticsController extends FOSRestController
{
    /**
     * @ApiDoc(
     *                           section="Statistics",
     *                           resource=true,
     *                           description="Get proposal",
     *                           statusCodes={
     *                           200="Returned when successful",
     *                           404={
     *                           "Returned when proposal not found",
     *                           }
     *                           }
     *                           )
     * @Rest\QueryParam(name="startAt", description="Start date d-m-Y format")
     * @Rest\QueryParam(name="endAt", description="End date d-m-Y format")
     * @Rest\Get("/statistics/participation")
     */
    public function getParticipationAction(ParamFetcher $paramFetcher)
    {
        $startAt  = \DateTime::createFromFormat('d-m-Y', $paramFetcher->get('startAt'));
        $endAt  = \DateTime::createFromFormat('d-m-Y', $paramFetcher->get('endAt'));
        $startAt->setTime(0, 0);
        $endAt->setTime(0, 0);

        return [$this->getStatisticsManager()->getStatistics($startAt, $endAt)];
    }

    public function getStatisticsManager()
    {
        return $this->get('app.statistics');
    }
}
